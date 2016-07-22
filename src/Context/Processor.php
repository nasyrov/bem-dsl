<?php namespace BEM\DSL\Context;

use BEM\DSL\Entity;
use BEM\DSL\Match\CompilerInterface;

class Processor implements ProcessorInterface
{
    protected $matchCompiler;
    protected $matcher;

    protected $nodes;

    public function __construct(CompilerInterface $matchCompiler)
    {
        $this->matchCompiler = $matchCompiler;
    }

    public function process($arr)
    {
        $result = [$arr];

        $this->nodes   = [];
        $this->nodes[] = new Entity([
            'index' => 0,
            'block' => null,
            'arr'   => $arr,
        ]);

        $this->matcher = $this->matcher ?: $this->matchCompiler->compile();

        $ctx = new Context;

        while ($node = array_shift($this->nodes)) {
            if (is_scalar($node->arr)) {
                continue;
            } elseif (is_array($node->arr)) {
                $this->children($node->arr, $node);
            } else {
                if ($node->arr->elem) {
                    $node->arr->block = $node->block;
                } elseif ($node->arr->block) {
                    $node->block = $node->arr->block;
                }

                $ctx->node = $node;
                $ctx->arr  = $node->arr;

                $res = $this->matcher($ctx, $node->arr);
                if (null !== $res) {
                    $node->arr     = $res;
                    $this->nodes[] = $node;

                    continue;
                }

                if (is_array($node->arr->content)) {
                    $this->children($node->arr->content, $node);
                } elseif ($node->arr->content) {
                    $this->nodes[] = new Entity([
                        'index'  => 'content',
                        'block'  => $node->block,
                        'arr'    => $node->arr->content,
                        'parent' => $node,
                    ]);
                }
            }

            $result[$node->index] = $node->arr;
        }

        return $result[0];
    }

    protected function children(array $children, $parent)
    {
        $position = 0;
        foreach ($children as $index => $child) {
            if (!$child instanceof Entity) {
                continue;
            }

            $this->nodes[] = new Entity([
                'index'    => $index,
                'position' => ++$position,
                'block'    => $parent->block,
                'arr'      => $child,
                'parent'   => $parent,
            ]);
        }

        $parent->length = $position;
    }
}
