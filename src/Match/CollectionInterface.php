<?php namespace BEM\DSL\Match;

use Closure;

interface CollectionInterface
{
    /**
     * @param string|array $expression
     * @param Closure $closure
     *
     * @return CollectionInterface
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
