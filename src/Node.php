<?php namespace Lego\DSL;

/**
 * Class Node.
 *
 * @package Lego\DSL
 */
class Node implements NodeInterface
{
    /**
     * Bricks.
     *
     * @var string|array
     */
    protected $bricks;

    /**
     * Index.
     *
     * @var int
     */
    protected $index;

    /**
     * Position.
     *
     * @var int
     */
    protected $position;

    /**
     * Parent.
     *
     * @var NodeInterface
     */
    protected $parent;

    public function index($index = null)
    {
        if (null === $index) {
            return $this->index;
        }

        $this->index = $index;

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

    public function bricks($bricks = null)
    {
        if (null === $bricks) {
            return $this->bricks;
        }

        $this->bricks = $bricks;

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
