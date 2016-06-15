<?php namespace Lego\DSL;

use Closure;

/**
 * Interface MatcherCollectionInterface.
 * @package Lego\DSL
 */
interface MatcherCollectionInterface
{
    /**
     * Adds new matcher.
     *
     * @param string|array $expression
     * @param Closure $closure
     *
     * @return MatcherCollectionInterface
     */
    public function add($expression, Closure $closure);

    /**
     * Gets expressions.
     *
     * @return array
     */
    public function expressions();

    /**
     * Gets closures.
     *
     * @return array
     */
    public function closures();
}
