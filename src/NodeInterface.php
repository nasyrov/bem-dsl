<?php namespace Lego\DSL;

/**
 * Interface NodeInterface.
 *
 * @package Lego\DSL
 */
interface NodeInterface
{
    /**
     * Sets and gets index.
     *
     * @param int $index
     *
     * @return int|NodeInterface
     */
    public function index($index);

    /**
     * Sets and gets bricks.
     *
     * @param string|array $bricks
     *
     * @return string|array|NodeInterface
     */
    public function bricks($bricks);

    /**
     * Sets and gets position.
     *
     * @param int $position
     *
     * @return int|NodeInterface
     */
    public function position($position);

    /**
     * Sets and gets parent.
     *
     * @param NodeInterface $parent
     *
     * @return NodeInterface
     */
    public function parent(NodeInterface $parent);
}
