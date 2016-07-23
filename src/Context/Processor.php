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
            $nodeBlock  = $node->block;
            $nodeBemArr = $node->bemArr;

            if (is_scalar($nodeBemArr)) {
                continue;
            } elseif (is_array($nodeBemArr)) {
                $this->children($nodeBemArr, $node);
                $result[$node->index] = $nodeBemArr;

                continue;
            }

            if ($nodeBemArr->elem) {
                $nodeBlock = $nodeBemArr->block = $nodeBemArr->block ?: $nodeBlock;
            } elseif ($node->bemArr->block) {
                $nodeBlock = $nodeBemArr->block;
            }

            if (!$nodeBemArr->stop) {
                $ctx = new Context($this, $node, $nodeBemArr);

                $subResult = $compiledMatcher($ctx, $node->bemArr);
                if (null !== $subResult) {
                    $node->bemArr = $subResult;
                    $node->block  = $nodeBlock;

                    $this->nodes[] = $node;

                    continue;
                }
            }

            if (is_array($nodeBemArr->content)) {
                $this->children($nodeBemArr->content, $node);
            } elseif ($nodeBemArr->content) {
                $this->nodes[] = new Entity([
                    'index'  => 'content',
                    'block'  => $nodeBlock,
                    'bemArr' => $nodeBemArr->content,
                    'parent' => $node,
                ]);
            }
        }

        var_dump($result[0]);

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
