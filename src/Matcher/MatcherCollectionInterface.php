<?php namespace Lego\DSL\Matcher;

use Closure;

/**
 * Interface MatcherCollectionInterface.
 * @package Lego\DSL\Matcher
 */
interface MatcherCollectionInterface
{
    /**
     * @param string|array $expression
     * @param Closure $closure
     *
     * @return MatcherCollectionInterface
     */
    public function add($expression, Closure $closure);

    /**
     * @return array
     */
    public function expressions();

    /**
     * @return array
     */
    public function closures();
}
