<?php namespace Lego\DSL\Context;

class ContextRender
{
    const SHORT_TAGS = [
        'area',
        'base',
        'br',
        'col',
        'command',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'menuitem',
        'meta',
        'param',
        'source',
        'track',
        'wbr',
    ];

    protected $classes = [];
    protected $attributes = [];
    protected $js = [];

    public function render($context)
    {
        if (is_scalar($context)) {
            return (string)$context;
        } elseif (is_array($context)) {
            return implode('', array_map(function ($context) {
                return $this->render($context);
            }, $context));
        } elseif ($context instanceof ContextInterface) {
            return $this->context($context);
        }
    }

    protected function context(ContextInterface $context)
    {
        $this->classes = $this->js = [];

        $this->attributes = $context->attributes() ?: [];

        if (false !== $context->bem()) {
            $base = $context->block() . ($context->element() ? '__' . $context->element() : '');

            if ($context->block()) {
                $this->classes += $this->bemClasses($context, $base);

                if ($context->js()) {
                    $this->js[$base] = true === $context->js() ? [] : $context->js();
                }
            }

            if ($context->mixes()) {
                foreach ($context->mixes() as $mix) {
                    if (!$mix instanceof ContextInterface || false === $mix->bem()) {
                        continue;
                    }

                    $mixBlock = $mix->block() ?: $context->block();
                    if (!$mixBlock) {
                        continue;
                    }

                    $mixElement = $mix->element() ?: ($mix->block() ? null : ($context->block() ? $context->element() : null));
                    $mixBase    = $mixBlock . ($mixElement ? '__' . $mixElement : '');

                    $this->classes += $this->bemClasses($mix, $mixBase, $base);

                    if ($mix->js()) {
                        $this->js[$mixBase] = true === $mix->js() ? [] : $mix->js();
                    }
                }
            }

            if ($this->js) {
                $this->classes[] = 'i-bem';

                $this->attributes['data-bem'] = json_encode($this->js);
            }
        }

        if ($context->classes()) {
            $this->classes += $context->classes();
        }

        $tag     = $context->tag() ?: 'div';
        $classes = $this->classes ? ' class="' . implode(' ', $this->classes) . '"' : '';

        if (in_array($tag, static::SHORT_TAGS)) {
            return sprintf(
                '<%s%s%s>',
                $tag,
                $classes,
                $this->renderAttributes()
            );
        }

        return sprintf(
            '<%1$s%2$s%3$s>%4$s</%1$s>',
            $tag,
            $classes,
            $this->renderAttributes(),
            $this->render($context->content())
        );
    }

    protected function applyMixes()
    {

    }

    protected function renderAttributes()
    {
        if (!$this->attributes) {
            return '';
        }

        return sprintf(' %s', implode(' ', array_map(
            [$this, 'renderAttribute'],
            array_keys($this->attributes),
            $this->attributes
        )));
    }

    protected function renderAttribute($key, $value)
    {
        if (is_int($key)) {
            return $value;
        }

        return sprintf('%s="%s"', $key, $this->escape($value));
    }

    protected function bemClasses(ContextInterface $context, $base, $parentBase = null)
    {
        $result = [];

        if ($parentBase !== $base) {
            $result[] = $base;
        }

        foreach ($context->modifiers() as $key => $value) {
            if (!$value) {
                continue;
            }

            $result[] = $base . '_' . $key . (true === $value ? '' : '_' . $value);
        }

        return $result;
    }

    protected function escape($value)
    {
        return str_replace(['&', '"'], ['&amp;', '&quot;'], $value);
    }
}
