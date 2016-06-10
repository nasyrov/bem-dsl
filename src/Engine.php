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
     * Renders
     *
     * @param ContextInterface $context
     *
     * @return string
     */
    public function render(ContextInterface $context)
    {
        return (new Element($this->resolveContext($context)))->render();
    }

    protected function resolveContext(ContextInterface $context)
    {
        $compiledMatchers = $this->getCompiledMatchers();

        $nodes[] = $context;

        /**
         * @var $node ContextInterface
         */
        while ($node = array_shift($nodes)) {
            /**
             * @var ContextInterface $child
             */
            foreach ($node->content() as $child) {
                $child instanceof ContextInterface || $nodes[] = $child;
            }

            $compiledMatchers($node);
        }

        return $context;
    }

    protected function getCompiledMatchers()
    {
        if (null === $this->compiledMatchers) {
            $this->compiledMatchers = (new MatcherCompiler($this->matchers))->compile();
        }

        return $this->compiledMatchers;
    }
}
