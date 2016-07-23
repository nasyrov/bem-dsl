<?php namespace BEM\DSL\Context;

use BEM\DSL\Entity\Entity;
use BEM\DSL\Entity\EntityInterface;
use BEM\DSL\Match\CompilerInterface;

class Processor implements ProcessorInterface
{
    protected $compiler;
    protected $matcher;
    protected $nodes;

    /**
     * Processor constructor.
     *
     * @param CompilerInterface $compiler
     */
    public function __construct(CompilerInterface $compiler)
    {
        $this->compiler = $compiler;
    }

    public function getMatcher()
    {
        if (null === $this->matcher) {
            $this->matcher = $this->compiler->compile();
        }

        return $this->matcher;
    }

    public function process($bemArr)
    {
        $result = [$bemArr];

        $this->nodes   = [];
        $this->nodes[] = new Entity([
            'index'  => 0,
            'block'  => null,
            'bemArr' => $bemArr,
        ]);

        $compiledMatcher = $this->getMatcher();

        while ($node = array_shift($this->nodes)) {
            if (is_scalar($node->bemArr)) {
                continue;
            } elseif (is_array($node->bemArr)) {
                $this->children($node->bemArr, $node);
                $result[$node->index] = $node->bemArr;

                continue;
            }

            if ($node->bemArr->elem) {
                $node->bemArr->block = $node->block;
            } elseif ($node->bemArr->block) {
                $node->block = $node->bemArr->block;
            }

            if (!$node->bemArr->stop) {
                $ctx = new Context($this, $node, $node->bemArr);

                $subResult = $compiledMatcher($ctx, $node->bemArr);
                if (null !== $subResult) {
                    $node->bemArr  = $subResult;
                    $this->nodes[] = $node;

                    continue;
                }
            }

            if (is_array($node->bemArr->content)) {
                $this->children($node->bemArr->content, $node);
            } elseif ($node->bemArr->content) {
                $this->nodes[] = new Entity([
                    'index'  => 'content',
                    'block'  => $node->block,
                    'bemArr' => $node->bemArr->content,
                    'parent' => $node,
                ]);
            }
        }

        return $result[0];
    }

    /**
     * @param array $children
     * @param EntityInterface $parent
     */
    protected function children(array $children, EntityInterface $parent)
    {
        $position = 0;
        foreach ($children as $index => $child) {
            if (!$child instanceof EntityInterface) {
                continue;
            }

            $this->nodes[] = new Entity([
                'index'    => $index,
                'position' => ++$position,
                'block'    => $parent->block,
                'bemArr'   => $child,
                'parent'   => $parent,
            ]);
        }

        $parent->length = $position;
    }
}
