<?php namespace BEM\DSL\Context;

use BEM\DSL\Entity\EntityInterface;

class Context
{
    protected static $lastGenId = 0;
    protected $processor;
    protected $node;
    protected $bemArr;
    protected $expandoId;

    public function __construct(ProcessorInterface $processor, EntityInterface $node, EntityInterface $bemArr)
    {
        $this->processor = $processor;
        $this->node      = $node;
        $this->bemArr    = $bemArr;
        $this->expandoId = time();
    }

    public function applyBase()
    {
        $block = $this->bemArr->block;

        $matcher   = $this->processor->getMatcher();
        $subResult = $matcher($this, $this->bemArr);
        if (null !== $subResult) {
            $this->bemArr      = $this->node[$this->node->index] = $subResult;
            $this->node->block = $block;
        }

        return $this;
    }

    public function stop()
    {
        $this->bemArr->stop = true;

        return $this;
    }

    public function generateId()
    {
        return 'uniq' . $this->expandoId . (++static::$lastGenId);
    }

    public function position()
    {
        return 'content' === $this->node->index ?
            1 :
            $this->node->position;
    }

    public function isFirst()
    {
        return 'content' === $this->node->index ||
               1 === $this->node->position;
    }

    public function isLast()
    {
        return 'content' === $this->node->index ||
               $this->node->length === $this->node->position;
    }

    public function param($key, $value = null, $force = false)
    {
        $node = $this->node;

        if (null === $value) {
            while ($node) {
                if (isset($node->params[$key])) {
                    return $node->params[$key];
                }

                $node = $node->parent;
            }

            return null;
        }

        if ($force || !isset($node->params[$key])) {
            $node->params[$key] = $value;
        }

        return $this;
    }

    public function tag($tag = null, $force = false)
    {
        if (null === $tag) {
            return $this->bemArr->tag;
        } elseif ($force || null === $this->bemArr->tag) {
            $this->bemArr->tag = $tag;
        }

        return $this;
    }

    public function mix(array $mix = null, $force = false)
    {
        if (null === $mix) {
            return $this->bemArr->mix;
        } elseif ($force || !$this->bemArr->mix) {
            $this->bemArr->mix = $mix;
        } else {
            $this->bemArr->mix += $mix;
        }

        return $this;
    }

    public function cls($cls = null, $force = false)
    {
        if (null === $cls) {
            return $this->bemArr->cls;
        } elseif ($force || null === $this->bemArr->cls) {
            $this->bemArr->cls = $cls;
        }

        return $this;
    }

    public function mod($key, $value = null, $force = false)
    {
        if (null === $value) {
            return isset($this->bemArr->mods[$key]) ?
                $this->bemArr->mods[$key] :
                null;
        } elseif ($force || !isset($this->bemArr->mods[$key])) {
            $this->bemArr->mods[$key] = $value;
        }

        return $this;
    }

    public function mods(array $values = null, $force = false)
    {
        if (null === $values) {
            return $this->bemArr->mods;
        }

        $this->bemArr->mods = $force ?
            $values + $this->bemArr->mods :
            $this->bemArr->mods + $values;

        return $this;
    }

    public function attr($key, $value = null, $force = false)
    {
        if (null === $value) {
            return isset($this->bemArr->attrs[$key]) ?
                $this->bemArr->attrs[$key] :
                null;
        } elseif ($force || !isset($this->bemArr->attrs[$key])) {
            $this->bemArr->attrs[$key] = $value;
        }

        return $this;
    }

    public function attrs(array $attrs = null, $force = false)
    {
        if (null === $attrs) {
            return $this->bemArr->attrs;
        }

        $this->bemArr->attrs || $this->bemArr->attrs = [];
        $this->bemArr->attrs = $force ?
            $attrs + $this->bemArr->attrs :
            $this->bemArr->attrs + $attrs;

        return $this;
    }

    public function bem($bem = null, $force = false)
    {
        if (null === $bem) {
            return $this->bemArr->bem;
        } elseif ($force || null === $this->bemArr->bem) {
            $this->bemArr->bem = $bem;
        }

        return $this;
    }

    public function js($js = null, $force = false)
    {
        if (null === $js) {
            return $this->bemArr->js;
        } elseif ($force || null === $this->bemArr->js) {
            $this->bemArr->js = $js;
        } else {
            $this->bemArr->js += $js;
        }

        return $this;
    }

    public function content($value = null, $force = false)
    {
        if (null === $value) {
            return $this->bemArr->content;
        } elseif ($force || null === $this->bemArr->content) {
            $this->bemArr->content = $value;
        }

        return $this;
    }
}
