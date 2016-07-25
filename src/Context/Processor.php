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
        $resArr = new \ArrayObject([$bemArr]);

        $this->nodes   = [];
        $this->nodes[] = new Entity([
            'index'  => 0,
            'block'  => null,
            'bemArr' => $resArr[0],
            'resArr' => $resArr,
        ]);

        $compiledMatcher = $this->getMatcher();

        while ($node = array_shift($this->nodes)) {
            $nodeBlock  = $node->block;
            $nodeBemArr = $node->bemArr;

            if (is_array($nodeBemArr)) {
                $this->children($nodeBemArr, $nodeBlock, $node);
                $node->resArr[$node->index] = $nodeBemArr;
            } elseif ($nodeBemArr instanceof EntityInterface) {
                if ($nodeBemArr->elem) {
                    $nodeBlock = $nodeBemArr->block = $nodeBemArr->block ?: $nodeBlock;
                } elseif ($nodeBemArr->block) {
                    $nodeBlock = $nodeBemArr->block;
                }

                if (!$nodeBemArr->stop) {
                    $ctx = new Context($this, $node, $nodeBemArr);

                    $subResArr = $compiledMatcher($ctx, $node->bemArr);
                    if (null !== $subResArr) {
                        $node->bemArr = $subResArr;
                        $node->block  = $nodeBlock;

                        $this->nodes[] = $node;

                        continue;
                    }
                }

                if (is_array($nodeBemArr->content)) {
                    $nodeBemArr->content = $this->flatten($nodeBemArr->content);
                    $this->children($nodeBemArr->content, $nodeBlock, $node);
                } elseif ($nodeBemArr->content) {
                    $this->nodes[] = new Entity([
                        'index'  => 'content',
                        'block'  => $nodeBlock,
                        'bemArr' => $nodeBemArr->content,
                        'resArr' => $nodeBemArr,
                        'parent' => $node,
                    ]);
                }
            }
        }

        return $resArr[0];
    }

    protected function flatten(array $array)
    {
        $result = [];

        foreach ($array as $value) {
            if (is_array($value)) {
                $result = array_merge($result, $value);
            } else {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * @param array $children
     * @param EntityInterface $parent
     */
    protected function children(array $children, $block, EntityInterface $parent)
    {
        $position = 0;
        foreach ($children as $index => $child) {
            if (!$child instanceof EntityInterface) {
                continue;
            }

            $this->nodes[] = new Entity([
                'index'    => $index,
                'position' => ++$position,
                'block'    => $block,
                'bemArr'   => $child,
                'resArr'   => $children,
                'parent'   => $parent,
            ]);
        }

        $parent->length = $position;
    }
}
