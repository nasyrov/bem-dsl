<?php namespace Lego\DSL;

/**
 * Class Context.
 * @package Lego\DSL
 */
class Context implements ContextInterface
{
    /**
     * Block name.
     * @var string
     */
    protected $block;
    /**
     * Element name.
     * @var string
     */
    protected $element;
    /**
     * Collection of mixes.
     * @var array
     */
    protected $mixes = [];
    /**
     * HTML tag name.
     * @var string
     */
    protected $tag;
    /**
     * Collection of CSS classes.
     * @var array
     */
    protected $classes = [];
    /**
     * Collection of modifiers.
     * @var array
     */
    protected $modifiers = [];
    /**
     * Collection of attributes.
     * @var array
     */
    protected $attributes = [];
    /**
     * BEM notation.
     * @var bool
     */
    protected $bem = true;
    /**
     * Collection of JS parameters.
     * @var bool|array
     */
    protected $js;
    /**
     * Content.
     * @var mixed
     */
    protected $content;
    /**
     * Node.
     * @var array
     */
    protected $node;
    /**
     * Collection of matchers.
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

    public function element($element = null)
    {
        if (null === $element) {
            return $this->element;
        }

        $this->element = $element;

        return $this;
    }

    public function mixes(array $mixes = null)
    {
        if (null === $mixes) {
            return $this->mixes;
        }

        $this->mixes += $mixes;

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

    public function classes(array $classes = null)
    {
        if (null === $classes) {
            return $this->classes;
        }

        $this->classes = array_merge($this->classes, $classes);

        return $this;
    }

    public function modifiers($key = null, $value = null)
    {
        if (null === $key) {
            return $this->modifiers;
        } elseif (is_array($key)) {
            $this->modifiers += $key;

            return $this;
        } elseif (null === $value) {
            return isset($this->modifiers[$key]) ? $this->modifiers[$key] : null;
        }

        $this->modifiers[$key] = $value;

        return $this;
    }

    public function attributes($key = null, $value = null)
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

    public function node(array $node = null)
    {
        if (null === $node) {
            return $this->node;
        }

        $this->node = $node;

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
