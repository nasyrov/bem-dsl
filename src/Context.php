<?php namespace Lego\DSL;

class Context
{
    public $node;
    public $arr;

    public function position()
    {
        return 'content' === $this->node->index ? 1 : ($this->node->position ?: null);
    }

    public function isFirst()
    {
        return 'content' === $this->node->index || 1 === $this->node->position;
    }

    public function isLast()
    {
        return 'content' === $this->node->index;
    }

    public function tParam($key, $value = null, $force = false)
    {
        $node = $this->node;
        $node->tParams || $node->tParams = [];

        if (null === $value) {
            while ($node) {
                if (isset($node->tParams[$key])) {
                    return $node->tParams[$key];
                }

                $node = $node->parent;
            }

            return null;
        }

        if ($force || !isset($node->tParams[$key])) {
            $node->tParams[$key] = $value;
        }

        return $this;
    }

    public function tag($tag = null, $force = false)
    {
        if (null === $tag) {
            return $this->arr->tag;
        } elseif ($force || null === $this->arr->tag) {
            $this->arr->tag = $tag;
        }

        return $this;
    }

    public function mix(array $mix = null, $force = false)
    {
        if (null === $mix) {
            return $this->arr->mix;
        } elseif ($force || !$this->arr->mix) {
            $this->arr->mix = $mix;
        } else {
            $this->arr->mix += $mix;
        }

        return $this;
    }

    public function attr($key, $value = null, $force = false)
    {
        if (null === $value) {
            return isset($this->arr->attrs[$key]) ? $this->arr->attrs[$key] : null;
        } elseif (!isset($this->arr->attrs[$key]) || $force) {
            $this->arr->attrs[$key] = $value;
        }

        return $this;
    }

    public function attrs(array $attrs = null, $force = false)
    {
        if (null === $attrs) {
            return $this->arr->attrs;
        }

        $this->arr->attrs || $this->arr->attrs = [];
        $this->arr->attrs = $force ? $attrs + $this->arr->attrs : $this->arr->attrs + $attrs;

        return $this;
    }

    public function bem($bem = null, $force = false)
    {
        if (null === $bem) {
            return $this->arr->bem;
        } elseif ($force || null === $this->arr->bem) {
            $this->arr->bem = $bem;
        }

        return $this;
    }

    public function js($js = null, $force = false)
    {
        if (null === $js) {
            return $this->arr->js;
        } elseif ($force || null === $this->arr->js) {
            $this->arr->js = $js;
        } else {
            $this->arr->js += $js;
        }

        return $this;
    }

    public function cls($cls = null, $force = false)
    {
        if (null === $cls) {
            return $this->arr->cls;
        } elseif ($force || null === $this->arr->cls) {
            $this->arr->cls = $cls;
        }

        return $this;
    }

    public function mod($key, $value = null, $force = false)
    {
        if (null === $value) {
            return isset($this->arr->mods[$key]) ? $this->arr->mods[$key] : null;
        } elseif ($force || !isset($this->arr->mods[$key])) {
            $this->arr->mods[$key] = $value;
        }

        return $this;
    }

    public function mods(array $values = null, $force = false)
    {
        if (null === $values) {
            return $this->arr->mods;
        }

        $this->arr->mods = $force ? $values + $this->arr->mods : $this->arr->mods + $values;

        return $this;
    }

    public function content($value = null, $force = false)
    {
        if (null === $value) {
            return $this->arr->content;
        } elseif ($force || null === $this->arr->content) {
            $this->arr->content = $value;
        }

        return $this;
    }

    public function param($key, $value = null, $force = false)
    {
        if (null === $value) {
            return isset($this->arr->params[$key]) ? $this->arr->params[$key] : null;
        } elseif ($force || !isset($this->arr->params[$key])) {
            $this->arr->params[$key] = $value;
        }

        return $this;
    }

    public function params(array $values = null, $force = false)
    {
        if (null === $values) {
            return $this->arr->params;
        }

        $this->arr->params = $force ? $values + $this->arr->params : $this->arr->params + $values;

        return $this;
    }
}
