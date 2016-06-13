<?php namespace Lego\DSL;

use ArrayAccess;
use Closure;
use Countable;
use IteratorAggregate;

/**
 * Interface MatcherIteratorInterface.
 *
 * @package Lego\DSL
 */
interface MatcherCollectionInterface extends ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @param string $expr
     * @param Closure $closure
     *
     * @return MatcherCollectionInterface
     */
    public function set($expr, Closure $closure);

    /**
     * @param string $expr
     * @param mixed $default
     *
     * @return mixed|MatcherInterface
     */
    public function get($expr, $default = null);

    /**
     * @param string $expr
     *
     * @return bool
     */
    public function has($expr);
}
