<?php namespace Lego\DSL;

/**
 * Class Element.
 * @package Lego\DSL
 */
class Element implements ElementInterface
{
    const VOID_TAG_TEMPLATE = '<%s%s>';
    const FULL_TAG_TEMPLATE = '<%1$s%2$s>%3$s</%1$s>';

    /**
     * Context instance.
     * @var ContextInterface
     */
    protected $context;
    /**
     * Short tags.
     * @var array
     */
    protected $voidTags = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr',
    ];

    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        if (in_array($this->context->tag(), $this->voidTags)) {
            return sprintf(
                static::VOID_TAG_TEMPLATE,
                $this->context->tag(),
                $this->renderAttributes()
            );
        }

        return sprintf(
            static::FULL_TAG_TEMPLATE,
            $this->context->tag(),
            $this->renderAttributes(),
            $this->renderChildren()
        );
    }

    /**
     * Render attributes.
     *
     * @return string
     */
    protected function renderAttributes()
    {
        $attributes = $this->context->attributes();

        if ($this->context->block() && $this->context->bem()) {
            $this->resolveBemAttributes($attributes);
        }

        if ($this->context->classes()) {
            $attributes['class'] = isset($attributes['class']) ?
                array_merge($attributes['class'], $this->context->classes()) :
                $this->context->classes();
        }

        ksort($attributes);

        return implode('', array_map(function ($key, $value) {
            return $this->renderAttribute($key, $value);
        }, array_keys($attributes), $attributes));
    }

    /**
     * Renders attribute.
     *
     * @param int|string $key
     * @param string $value
     *
     * @return string
     */
    protected function renderAttribute($key, $value)
    {
        if (is_int($key)) {
            return sprintf(' %s', $key);
        } elseif (is_array($value)) {
            $value = implode(' ', $value);
        }

        return sprintf(' %s="%s"', $key, $this->entities($value));
    }

    /**
     * Resolves BEM attributes.
     *
     * @param array $attributes
     */
    protected function resolveBemAttributes(array &$attributes)
    {
        $base = $this->context->block() . ($this->context->element() ? '__' . $this->context->element() : '');

        $classes = $this->resolveBemClasses($base);

        if ($this->context->js()) {
            $classes[] = 'i-bem';

            $jsParams[$base] = (true === $this->context->js()) ? [] : $this->context->js();

            $attributes['data-bem'] = json_encode($jsParams);
        }

        $attributes['class'] = $classes;
    }

    /**
     * Resolves BEM classes.
     *
     * @param string $base
     *
     * @return array
     */
    protected function resolveBemClasses($base)
    {
        $classes[] = $base;

        foreach ($this->context->modifiers() as $key => $value) {
            if (!$value) {
                continue;
            }

            $classes[] = $base . '_' . $key . (true === $value ? '' : '_' . $value);
        }

        return $classes;
    }

    /**
     * Renders children.
     *
     * @return string
     */
    protected function renderChildren()
    {
        if (is_array($this->context->content())) {
            return join('', array_map(function ($child) {
                return $this->renderChild($child);
            }, $this->context->content()));
        }

        return $this->renderChild($this->context->content());
    }

    /**
     * Renders child.
     *
     * @param mixed $child
     *
     * @return string
     */
    protected function renderChild($child)
    {
        if (is_string($child)) {
            return $this->entities($child);
        } elseif ($child instanceof ContextInterface) {
            return new static($child);
        }

        return '';
    }

    /**
     * Converts characters to HTML entities.
     *
     * @param string $value
     *
     * @return string
     */
    protected function entities($value)
    {
        return htmlentities($value, ENT_QUOTES, 'utf-8');
    }
}
