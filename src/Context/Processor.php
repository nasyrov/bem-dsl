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
                $position = 0;
                foreach ($nodeBemArr as $index => $child) {
                    if (!$child instanceof EntityInterface) {
                        continue;
                    }

                    $this->nodes[] = new Entity([
                        'index'    => $index,
                        'position' => ++$position,
                        'block'    => $nodeBlock,
                        'bemArr'   => $child,
                        'parent'   => $node,
                    ]);
                }

                $node->length = $position;

                $node->resArr[$node->index] = $nodeBemArr;
            } elseif ($nodeBemArr instanceof EntityInterface) {
                if ($nodeBemArr->elem) {
                    $nodeBlock = $nodeBemArr->block = $nodeBemArr->block ?: $nodeBlock;
                } elseif ($nodeBemArr->block) {
                    $nodeBlock = $nodeBemArr->block;
                }

                $stopProcess = false;
                if (!$nodeBemArr->stop) {
                    $ctx = new Context($this, $node, $nodeBemArr);

                    $subResult = $compiledMatcher($ctx, $node->bemArr);
                    if (null !== $subResult) {
                        $nodeBemArr = $subResult;

                        $node->bemArr = $nodeBemArr;
                        $node->block  = $nodeBlock;

                        $this->nodes[] = $node;

                        $stopProcess = true;
                    }
                }

                if (!$stopProcess) {
                    if (is_array($nodeBemArr->content)) {
                        $position = 0;
                        foreach ($nodeBemArr->content as $index => $child) {
                            if (!$child instanceof EntityInterface) {
                                continue;
                            }

                            $this->nodes[] = new Entity([
                                'index'    => $index,
                                'position' => ++$position,
                                'block'    => $nodeBlock,
                                'bemArr'   => $child,
                                'parent'   => $node,
                            ]);
                        }

                        $node->length = $position;
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
        }

        var_dump($resArr[0]);

        return $resArr[0];
    }

    protected function flatten(array $array)
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                echo 'flattening';
                var_dump($value);
                $result += $this->flatten($value);
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
