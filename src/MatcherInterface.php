<?php namespace Lego\DSL;

use Closure;

/**
 * Interface MatcherInterface.
 *
 * @package Lego\DSL
 */
interface MatcherInterface
{
    /**
     * Sets and gets the expression.
     *
     * @param null|string $expression
     *
     * @return string|MatcherInterface
     */
    public function expression($expression);

    /**
     * Sets and gets the closure.
     *
     * @param null|Closure $closure
     *
     * @return Closure|MatcherInterface
     */
    public function closure(Closure $closure);
}
