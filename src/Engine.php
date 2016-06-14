<?php namespace Lego\DSL;

use Closure;

/**
 * Class Engine.
 * @package Lego\DSL
 */
class Engine
{
    /**
     * Compiled matchers.
     *
     * @var Closure
     */
    protected $compiledMatchers;
    /**
     * Short tags.
     *
     * @var array
     */
    protected $shortTags = [
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
    /**
     * DirectoryCollection instance.
     * @var DirectoryCollectionInterface
     */
    protected $directoryCollection;
    /**
     * MatcherCollection instance.
     * @var MatcherCollectionInterface
     */
    protected $matcherCollection;

    /**
     * Creates new Engine instance.
     */
    public function __construct()
    {
        $this->directoryCollection = new DirectoryCollection;
        $this->matcherCollection   = new MatcherCollection;
    }

    /**
     * Add directory.
     *
     * @param string|array $path
     *
     * @return $this
     */
    public function addDirectory($path)
    {
        $this->directoryCollection->add($path);

        return $this;
    }

    /**
     * Registers new matcher
     *
     * @param string|array $expression
     * @param Closure $closure
     *
     * @return Engine
     */
    public function registerMatcher($expression, Closure $closure)
    {
        $this->matcherCollection->add($expression, $closure);

        // reset compiled matchers
        $this->compiledMatchers = null;

        return $this;
    }

    /**
     * Render.
     *
     * @param ContextInterface $context
     *
     * @return array
     */
    public function render(ContextInterface $context)
    {
        return $this->toHtml($this->process($context));
    }

    protected function process(ContextInterface $context)
    {
        $compiledMatchers = $this->getCompiledMatchers();

        $result = [$context];

        $nodes[] = [
            'index'   => 0,
            'block'   => null,
            'context' => $context,
            'result'  => $result,
        ];

        while ($node = array_pop($nodes)) {
            $nodeBlock   = $node['block'];
            $nodeContext = $node['context'];

            if (is_array($nodeContext)) {
                foreach ($nodeContext as $index => $child) {
                    if (!$child instanceof ContextInterface) {
                        continue;
                    }

                    $nodes[] = [
                        'index'   => $index,
                        'block'   => $nodeBlock,
                        'context' => $child,
                        'result'  => $nodeContext,
                    ];
                }

                $result[$node['index']] = $nodeContext;

                continue;
            }

            if ($nodeContext->element()) {
                $nodeBlock = $nodeContext->block() ?: $nodeBlock;
                $nodeContext->block($nodeBlock);
            } elseif ($nodeContext->block()) {
                $nodeBlock = $nodeContext->block();
            }

            $compiledResult = $compiledMatchers($nodeContext);
            if (null !== $compiledResult) {
                $nodeContext = $compiledResult;

                $node['block']   = $nodeBlock;
                $node['context'] = $nodeContext;

                $nodes[] = $node;

                continue;
            }

            if ($nodeContext->content()) {
                if (is_array($nodeContext->content())) {
                    foreach ($nodeContext->content() as $index => $child) {
                        if (!$child instanceof ContextInterface) {
                            continue;
                        }

                        $nodes[] = [
                            'index'   => $index,
                            'block'   => $nodeBlock,
                            'context' => $child,
                            'result'  => $nodeContext,
                        ];
                    }
                } else {
                    $nodes[] = [
                        'index'   => 'content',
                        'block'   => $nodeBlock,
                        'context' => $nodeContext->content(),
                        'result'  => $nodeContext,
                    ];
                }
            }
        }

        return $result[0];
    }

    protected function toHtml($context)
    {
        if (is_scalar($context) || empty($context)) {
            return $context;
        } elseif (is_array($context)) {
            return join('', array_map(function ($context) {
                return $this->toHtml($context);
            }, $context));
        }

        if (!$context->tag() && $context->content()) {
            return $this->toHtml($context->content());
        }

        $classes    = '';
        $attributes = '';
        $jsParams   = [];

        if ($context->attributes()) {
            foreach ($context->attributes() as $key => $value) {
                if (true === $value) {
                    $attributes .= ' ' . $key;
                } elseif ($value) {
                    $attributes .= ' ' . $key . '="' . $value . '"';
                }
            }
        }

        if ($context->bem()) {
            $base = $context->block() . ($context->element() ? '__' . $context->element() : '');

            if ($context->block()) {
                $classes = $this->resolveBemCssClasses($context, $base);
                if ($context->js()) {
                    $jsParams[$base] = true === $context->js() ? [] : $context->js();
                }
            }

            if ($jsParams) {
                $classes .= ' i-bem';
                $attributes .= ' data-bem="' . json_encode($jsParams) . '"';
            }
        }

        if ($context->classes()) {
            $classes = ($classes ? $classes . ' ' : '') . join(' ', $context->classes());
        }

        $tag = $context->tag() ?: 'div';

        $result = '<' . $tag . ($classes ? ' class="' . $classes . '"' : '') . ($attributes ?: '');

        if (in_array($tag, $this->shortTags)) {
            $result .= '>';
        } else {
            $result .= '>';
            if ($context->content()) {
                $result .= $this->toHtml($context->content());
            }
            $result .= '</' . $tag . '>';
        }

        return $result;
    }

    protected function getCompiledMatchers()
    {
        if (null === $this->compiledMatchers) {
            $this->compiledMatchers = (new MatcherCompiler($this->matcherCollection))->compile();
        }

        return $this->compiledMatchers;
    }

    protected function resolveBemCssClasses(ContextInterface $context, $base)
    {
        $cssClasses = $base;

        foreach ($context->modifiers() as $modName => $modValue) {
            if (!$modValue) {
                continue;
            }

            $cssClasses .= ' ' . $base . '_' . $modName . (true === $modValue ? '' : '_' . $modValue);
        }

        return $cssClasses;
    }
}
