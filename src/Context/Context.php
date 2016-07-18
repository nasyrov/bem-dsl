<?php namespace Lego\DSL\Context;

use Lego\DSL\Node\NodeInterface;

class Context implements ContextInterface
{
    protected $tag;
    protected $classes = [];
    protected $attributes = [];
    protected $content;
    protected $block;
    protected $element;
    protected $mixes = [];
    protected $modifiers = [];
    protected $bem;
    protected $js;
    protected $node;
    protected $matchers = [];

    public function tag($tag = null, $force = false)
    {
        if (null === $tag) {
            return $this->tag;
        } elseif (null === $this->tag || $force) {
            $this->tag = $tag;
        }

        return $this;
    }

    public function classes(array $classes = null, $force = false)
    {
        if (null === $classes) {
            return $this->classes;
        } elseif (!$this->classes || $force) {
            $this->classes = array_merge($this->classes, $classes);
        }

        return $this;
    }

    public function attributes($key = null, $value = null, $force = false)
    {
        if (null === $key) {
            return $this->attributes;
        } elseif (is_array($key)) {
            foreach ($key as $_key => $_value) {
                $this->attributes($_key, $_value, (bool)$value);
            }
        } elseif (!isset($this->attributes[$key]) || $force) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    public function content($content = null, $force = false)
    {
        if (null === $content) {
            return $this->content;
        } elseif (null === $this->content || $force) {
            $this->content = $content;
        }

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

    public function element($element = null)
    {
        if (null === $element) {
            return $this->element;
        }

        $this->element = $element;

        return $this;
    }

    public function mixes(array $mixes = null, $force = false)
    {
        if (null === $mixes) {
            return $this->mixes;
        } elseif (!$this->mixes || $force) {
            $this->mixes = array_merge($this->mixes, $mixes);
        }

        return $this;
    }

    public function modifiers($key = null, $value = null, $force = false)
    {
        if (null === $key) {
            return $this->modifiers;
        } elseif (is_array($key)) {
            foreach ($key as $_key => $_value) {
                $this->modifiers($_key, $_value, (bool)$value);
            }
        } elseif (null === $value) {
            return isset($this->modifiers[$key]) ? $this->modifiers[$key] : null;
        } elseif (!isset($this->modifiers[$key]) || $force) {
            $this->modifiers[$key] = $value;
        }

        return $this;
    }

    public function bem($bem = null, $force = false)
    {
        if (null === $bem) {
            return $this->bem;
        } elseif (null === $this->bem || $force) {
            $this->bem = $bem;
        }

        return $this;
    }

    public function js($js = null, $force = false)
    {
        if (null === $js) {
            return $this->js;
        } elseif (null === $this->js || $force) {
            $this->js = $js;
        } elseif (false !== $this->js) {
            $this->js = array_merge($this->js, $js);
        }

        return $this;
    }

    public function node(NodeInterface $node = null)
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
        } elseif (null === $value) {
            return isset($this->matchers[$key]) ? $this->matchers[$key] : false;
        }

        $this->matchers[$key] = (bool)$value;

        return $this;
    }
}
