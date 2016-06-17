<?php namespace Lego\DSL;

use Closure;

/**
 * Interface EngineInterface.
 * @package Lego\DSL
 */
interface EngineInterface
{
    /**
     * Adds a directory path.
     *
     * @param string|array $path
     *
     * @return EngineInterface
     */
    public function directory($path);

    /**
     * Registers a matcher with the specified expression.
     *
     * @param string|array $expression
     * @param Closure $closure
     *
     * @return EngineInterface
     */
    public function matcher($expression, Closure $closure);

    /**
     * Renders given context.
     *
     * @param string|ContextInterface|array $context
     *
     * @return string
     */
    public function render($context);
}
