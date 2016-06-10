<?php namespace Lego\DSL;

use Closure;

/**
 * Interface MatcherCollectionInterface.
 *
 * @package Lego\DSL
 */
interface MatcherCollectionInterface
{
    /**
     * Adds new matcher
     *
     * @param string $expression
     * @param Closure $closure
     *
     * @return MatcherCollectionInterface
     */
    public function add($expression, Closure $closure);

    public function toArray();
}
