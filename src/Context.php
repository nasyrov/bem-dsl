<?php namespace Lego\DSL;

/**
 * Class Context.
 *
 * @package Lego\DSL
 */
class Context implements ContextInterface
{
    /**
     * Block name.
     *
     * @var string
     */
    protected $block;

    /**
     * Element name.
     *
     * @var string
     */
    protected $element;

    /**
     * Mixed context.
     *
     * @var array
     */
    protected $mix = [];

    /**
     * HTML tag name.
     *
     * @var string
     */
    protected $tag;

    /**
     * Collection of CSS classes.
     *
     * @var array
     */
    protected $classes = [];

    /**
     * Collection of modificators.
     *
     * @var array
     */
    protected $mods = [];

    /**
     * Collection of attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * BEM notation.
     *
     * @var bool
     */
    protected $bem = true;

    /**
     * Collection of JS parameters.
     *
     * @var bool|array
     */
    protected $js;

    /**
     * Context content.
     *
     * @var mixed
     */
    protected $content;

    /**
     * Collection of matchers.
     *
     * @var array
     */
    protected $matchers = [];

    public function block($block = null)
    {
        if (null === $block) {
            return $this->block;
        }

        $this->block = $block;

        return $this;
    }

    public function elem($elem = null)
    {
        if (null === $elem) {
            return $this->element;
        }

        $this->element = $elem;

        return $this;
    }

    public function mix(array $mix = null)
    {
        if (null === $mix) {
            return $this->mix;
        }

        $this->mix += $mix;

        return $this;
    }

    public function tag($tag = null)
    {
        if (null === $tag) {
            return $this->tag;
        }

        $this->tag = $tag;

        return $this;
    }

    public function cls(array $cls = null)
    {
        if (null === $cls) {
            return $this->classes;
        }

        $this->classes += $cls;

        return $this;
    }

    public function mods($key = null, $value = null)
    {
        if (null === $key) {
            return $this->mods;
        } elseif (is_array($key)) {
            $this->mods += $key;

            return $this;
        } elseif (null === $value) {
            return isset($this->mods[$key]) ? $this->mods[$key] : null;
        }

        $this->mods[$key] = $value;

        return $this;
    }

    public function attrs($key = null, $value = null)
    {
        if (null === $key) {
            return $this->attributes;
        } elseif (is_array($key)) {
            $this->attributes += $key;

            return $this;
        } elseif (null === $value) {
            return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    public function bem($bem = null)
    {
        if (null === $bem) {
            return $this->bem;
        }

        $this->bem = $bem;

        return $this;
    }

    public function js($js = null)
    {
        if (null === $js) {
            return $this->js;
        }

        $this->js = $js;

        return $this;
    }

    public function content(...$content)
    {
        if (!$content) {
            return $this->content;
        }

        $this->content = $content;

        return $this;
    }

    public function matchers($key = null, $value = null)
    {
        if (null === $key) {
            return $this->matchers;
        } elseif (is_array($key)) {
            $this->matchers += $key;

            return $this;
        } elseif (null === $value) {
            return isset($this->matchers[$key]) ? $this->matchers[$key] : false;
        }

        $this->matchers[$key] = $value;

        return $this;
    }
}
