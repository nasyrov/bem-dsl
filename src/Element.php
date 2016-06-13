<?php namespace Lego\DSL;

class Element
{
    const SINGLE_TAG_TEMPLATE = '<%s%s>';
    const DOUBLE_TAG_TEMPLATE = '<%1$s%2$s>%3$s</%1$s>';

    protected static $selfClosingTags = [
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

    protected $context;

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
        return sprintf(
            $this->getTemplate(),
            $this->context->tag(),
            $this->renderAttributes(),
            $this->renderContent()
        );
    }

    protected function getTemplate()
    {
        if (in_array($this->context->tag(), static::$selfClosingTags)) {
            return static::SINGLE_TAG_TEMPLATE;
        }

        return static::DOUBLE_TAG_TEMPLATE;
    }

    protected function renderAttributes()
    {
        $attributes = $this->context->attributes();

        $cssClasses = [];

        if ($this->context->bem()) {
            $jsParams = [];

            $base = $this->context->block() . ($this->context->element() ? '__' . $this->context->element() : '');

            if ($this->context->block()) {
                $cssClasses[] = $this->resolveBemCssClasses($base, null, false);
                if ($this->context->jsParams()) {
                    $jsParams[$base] = $this->context->jsParams();
                }
            }

            if ($this->context->mixes()) {
                foreach ($this->context->mixes() as $key => $value) {
                }
            }

            if ($jsParams) {
                $cssClasses[]           = 'i-bem';
                $attributes['data-bem'] = json_encode($jsParams);
            }
        }

        $this->context->classes() || $cssClasses += $this->context->classes();

        $cssClasses && $attributes['class'] = join(' ', $cssClasses);

        return join('', array_map(function ($key, $value) {
            return $this->renderAttribute($key, $value);
        }, array_keys($attributes), $attributes));
    }

    protected function renderAttribute($key, $value)
    {
        if ('' === $value || false == $value) {
            return '';
        } elseif (true === $value || is_int($key)) {
            return sprintf(' %s', $key);
        }

        return sprintf(' %s="%s"', $key, $value);
    }

    protected function resolveBemCssClasses($base, $parentBase = null, $noBase = false)
    {
        $cssClasses = '';

        if ($parentBase !== $base) {
            $cssClasses .= $parentBase ? ' ' : $base;
        }

        foreach ($this->context->modifiers() as $key => $value) {
            $cssClasses .= ' ' . ($noBase ? '' : $base) . '_' . $key . ($value === true ? '' : '_' . $value);
        }

        return $cssClasses;
    }

    protected function renderContent()
    {
        if (is_array($this->context->content())) {
            return join('', array_map(function ($content) {
                return $this->resolveContent($content);
            }, $this->context->content()));
        }

        return $this->resolveContent($this->context->content());
    }

    protected function resolveContent($content)
    {
        if (is_string($content)) {
            return htmlentities($content, ENT_HTML5, 'UTF-8');
        } elseif ($content instanceof ContextInterface) {
            return new static($content);
        }

        return '';
    }
}
