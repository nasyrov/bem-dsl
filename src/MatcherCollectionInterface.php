<?php namespace Lego\DSL;

use Closure;
use IteratorAggregate;

/**
 * Interface MatcherCollectionInterface.
 *
 * @package Lego\DSL
 */
interface MatcherCollectionInterface extends IteratorAggregate
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

    /**
     * Gets matcher
     *
     * @param int $key
     *
     * @return null|MatcherInterface
     */
    public function get($key);
}
