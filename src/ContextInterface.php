<?php namespace Lego\DSL;

/**
 * Interface ContextInterface.
 *
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
     * Sets and gets elem name.
     *
     * @param string $elem
     *
     * @return string|ContextInterface
     */
    public function elem($elem);

    /**
     * Sets and gets mix.
     *
     * @param array $mix
     *
     * @return array|ContextInterface
     */
    public function mix(array $mix);

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
     * @param array $cls
     *
     * @return array|ContextInterface
     */
    public function cls(array $cls);

    /**
     * Sets and gets modifications.
     *
     * @param string|array $key
     * @param string $value
     *
     * @return string|array|ContextInterface
     */
    public function mods($key, $value);

    /**
     * Set and gets attributes.
     *
     * @param string|array $key
     * @param string $value
     *
     * @return string|array|ContextInterface
     */
    public function attrs($key, $value);

    /**
     * Sets and gets BEM.
     *
     * @param bool $bem
     *
     * @return bool|ContextInterface
     */
    public function bem($bem);

    /**
     * Sets and gets JS parameters.
     *
     * @param bool|array $jsParams
     *
     * @return bool|array|ContextInterface
     */
    public function jsParams($jsParams);

    /**
     * Sets and gets content.
     *
     * @param array $content
     *
     * @return array|ContextInterface
     */
    public function content(...$content);

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
