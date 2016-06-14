<?php namespace Lego\DSL;

use Closure;
use IteratorAggregate;

/**
 * Interface MatcherCollectionInterface.
 * @package Lego\DSL
 */
interface MatcherCollectionInterface extends IteratorAggregate
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
    public function getExpressions();

    /**
     * Gets closures.
     *
     * @return array
     */
    public function getClosures();
}
