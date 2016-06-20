<?php namespace Lego\DSL\Node;

interface NodeInterface
{
    /**
     * @param null|int $index
     *
     * @return int|NodeInterface
     */
    public function index($index);

    /**
     * @param null|string $block
     *
     * @return string|NodeInterface
     */
    public function block($block);

    /**
     * @param null|int $position
     *
     * @return int|NodeInterface
     */
    public function position($position);

    /**
     * @param mixed $content
     *
     * @return mixed|NodeInterface
     */
    public function content($content);

    /**
     * @param null|mixed $result
     *
     * @return array|NodeInterface
     */
    public function result($result);

    /**
     * @param null|NodeInterface $parent
     *
     * @return NodeInterface
     */
    public function parent(NodeInterface $parent);
}
