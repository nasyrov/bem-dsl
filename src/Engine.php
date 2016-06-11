<?php namespace Lego\DSL;

use Closure;

/**
 * Class Engine
 *
 * @package Lego\DSL
 */
class Engine
{
    /**
     * Collection of matchers.
     *
     * @var MatcherInterface[]
     */
    protected $matchers;

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
     * Registers new matcher
     *
     * @param string|array $expression
     * @param Closure $closure
     *
     * @return Engine
     */
    public function registerMatcher($expression, Closure $closure)
    {
        if (is_array($expression)) {
            return array_map(function ($expression) use ($closure) {
                return $this->registerMatcher($expression, $closure);
            }, $expression);
        }

        $this->matchers[] = new Matcher($expression, $closure);

        // reset compiled matchers
        $this->compiledMatchers = null;

        return $this;
    }

    /**
     * Render.
     *
     * @param array ...$bricks
     *
     * @return array
     */
    public function render(...$bricks)
    {
        return $this->toHtml($this->process($bricks));
    }

    protected function process(array $bricks)
    {
        $compiledMatchers = $this->getCompiledMatchers();

        $result = $bricks;

        $nodes[] = [
            'index'  => 0,
            'block'  => null,
            'result' => $result,
        ];

        while ($node = array_shift($nodes)) {
            $nodeBlock  = $node['block'];
            $nodeResult = $node['result'];

            if (is_array($nodeResult)) {
                foreach ($nodeResult as $index => $child) {
                    $nodes[] = [
                        'index'  => $index,
                        'block'  => $nodeBlock,
                        'result' => $child,
                    ];
                }
            } else {
                $stopProcess = false;

                if (is_scalar($nodeResult)) {
                    continue;
                } elseif ($nodeResult->elem()) {
                    $nodeBlock = $nodeResult->block() ?: $nodeBlock;
                    $nodeResult->block($nodeBlock);
                } elseif ($nodeResult->block()) {
                    $nodeBlock = $nodeResult->block();
                }

                $compiledResult = $compiledMatchers($nodeResult);
                if (null !== $compiledResult) {
                    $nodeResult = $compiledResult;

                    $node['block']  = $nodeBlock;
                    $node['result'] = $nodeResult;

                    $nodes[] = $node;

                    $stopProcess = true;
                }

                if (!$stopProcess && $nodeResult->content()) {
                    if (is_array($nodeResult->content())) {
                        foreach ($nodeResult->content() as $index => $child) {
                            $nodes[] = [
                                'index'  => $index,
                                'block'  => $nodeBlock,
                                'result' => $child,
                            ];
                        }

                        continue;
                    }

                    $nodes[] = [
                        'index'  => 'content',
                        'block'  => $nodeBlock,
                        'result' => $nodeResult->content(),
                    ];
                }
            }

            $node['result'] = $nodeResult;
        }

        return $result;
    }

    protected function toHtml($context)
    {
        if (is_scalar($context)) {
            return $context;
        } elseif (is_array($context)) {
            return join('', array_map(function ($context) {
                return $this->toHtml($context);
            }, $context));
        }

        if (!$context->tag() && $context->content()) {
            return $this->toHtml($context->content());
        }

        $cls      = '';
        $attrs    = '';
        $jsParams = [];

        if ($context->attrs()) {
            foreach ($context->attrs() as $attrName => $attrValue) {
                if (true === $attrValue) {
                    $attrs .= ' ' . $attrName;
                } elseif ($attrValue) {
                    $attrs .= ' ' . $attrName . '="' . $attrValue . '"';
                }
            }
        }

        if ($context->bem()) {
            $base = $context->block() . ($context->elem() ? '__' . $context->elem() : '');

            if ($context->block()) {
                $cls = $this->resolveBemCssClasses($context, $base);
                if ($context->js()) {
                    $jsParams[$base] = true === $context->js() ? [] : $context->js();
                }
            }

            if ($jsParams) {
                $cls .= ' i-bem';
                $attrs .= ' data-bem="' . json_encode($jsParams) . '"';
            }
        }

        if ($context->cls()) {
            $cls = ($cls ? $cls . ' ' : '') . join(' ', $context->cls());
        }

        $tag = $context->tag() ?: 'div';

        $result = '<' . $tag . ($cls ? ' class="' . $cls . '"' : '') . ($attrs ?: '');

        if (in_array($tag, $this->shortTags)) {
            $result .= '/>';
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
            $this->compiledMatchers = (new MatcherCompiler($this->matchers))->compile();
        }

        return $this->compiledMatchers;
    }

    protected function resolveBemCssClasses(ContextInterface $context, $base)
    {
        $cssClasses = $base;

        foreach ($context->mods() as $modName => $modValue) {
            if (!$modValue) {
                continue;
            }

            $cssClasses .= ' ' . $base . '_' . $modName . (true === $modValue ? '' : '_' . $modValue);
        }

        return $cssClasses;
    }
}
