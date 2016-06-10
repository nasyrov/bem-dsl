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
            $this->renderAttrs(),
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

    protected function renderAttrs()
    {
        $attrs = $this->context->attrs();

        $this->resolveBemAttrs($attrs);
        $this->resolveClsAttrs($attrs);

        return join('', array_map(function ($key, $value) {
            return $this->renderAttr($key, $value);
        }, array_keys($attrs), $attrs));
    }

    protected function resolveBemAttrs()
    {
        if (!$this->context->bem()) {
            return;
        }
    }

    protected function resolveClsAttrs(array &$attrs)
    {
        $attrs['class'] = join(' ', $this->context->cls());
    }

    protected function renderAttr($key, $value)
    {
        if ('' === $value || false == $value) {
            return '';
        } elseif (true === $value || is_int($key)) {
            return sprintf(' %s', $key);
        }

        return sprintf(' %s="%s"', $key, $value);
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
