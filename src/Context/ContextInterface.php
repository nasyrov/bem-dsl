<?php namespace Lego\DSL\Context;

use Lego\DSL\Node\NodeInterface;

interface ContextInterface
{
    public function tag($tag, $force);

    public function classes(array $classes, $force);

    public function attributes($key, $value, $force);

    public function content($content, $force);

    public function block($block);

    public function element($element);

    public function bem($bem, $force);

    public function mixes(array $mixes);

    public function modifiers($key, $value, $force);

    public function js($js, $force);

    public function node(NodeInterface $node);
}
