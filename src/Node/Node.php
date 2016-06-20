<?php namespace Lego\DSL\Node;

class Node implements NodeInterface
{
    protected $index = 0;
    protected $block;
    protected $position = 0;
    protected $content;
    protected $result;
    protected $parent;

    public function index($index = null)
    {
        if (null === $index) {
            return $this->index;
        }

        $this->index = $index;

        return $this;
    }

    public function block($block = null)
    {
        if (null === $block) {
            return $this->block;
        }

        $this->block = $block;

        return $this;
    }

    public function position($position = null)
    {
        if (null === $position) {
            return $this->position;
        }

        $this->position = $position;

        return $this;
    }

    public function content($content = null)
    {
        if (null === $content) {
            return $this->content;
        }

        $this->content = $content;

        return $this;
    }

    public function result($result = null)
    {
        if (null === $result) {
            return $this->result;
        }

        $this->result = $result;

        return $this;
    }

    public function parent(NodeInterface $parent = null)
    {
        if (null === $parent) {
            return $this->parent;
        }

        $this->parent = $parent;

        return $this;
    }
}
