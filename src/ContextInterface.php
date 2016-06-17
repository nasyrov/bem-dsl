<?php namespace Lego\DSL;

/**
 * Interface ContextInterface.
 * @package Lego\DSL
 */
interface ContextInterface
{
    /**
     * Sets and gets block name.
     *
     * @param string $block
     *
     * @return string|ContextInterface
     */
    public function block($block);

    /**
     * Sets and gets element name.
     *
     * @param string $element
     *
     * @return string|ContextInterface
     */
    public function element($element);

    /**
     * Sets and gets mixes.
     *
     * @param array $mixes
     *
     * @return array|ContextInterface
     */
    public function mixes(array $mixes);

    /**
     * Sets and gets tag.
     *
     * @param string $tag
     *
     * @return string|ContextInterface
     */
    public function tag($tag);

    /**
     * Sets and gets classes.
     *
     * @param array $classes
     *
     * @return array|ContextInterface
     */
    public function classes(array $classes);

    /**
     * Sets and gets modifiers.
     *
     * @param string|array $key
     * @param string $value
     *
     * @return string|array|ContextInterface
     */
    public function modifiers($key, $value);

    /**
     * Set and gets attributes.
     *
     * @param string|array $key
     * @param string $value
     *
     * @return string|array|ContextInterface
     */
    public function attributes($key, $value);

    /**
     * Toggles on/off BEM.
     *
     * @param bool $bem
     *
     * @return bool|ContextInterface
     */
    public function bem($bem);

    /**
     * Sets and gets JS parameters.
     *
     * @param bool|array $js
     *
     * @return bool|array|ContextInterface
     */
    public function js($js);

    /**
     * Sets and gets content.
     *
     * @param array $content
     *
     * @return array|ContextInterface
     */
    public function content(...$content);

    /**
     * Sets and gets node.
     *
     * @param null|array $node
     *
     * @return array|ContextInterface
     */
    public function node(array $node);

    /**
     * Sets and gets matchers.
     *
     * @param string $key
     * @param true $value
     *
     * @return bool|array|ContextInterface
     */
    public function matchers($key, $value);
}
