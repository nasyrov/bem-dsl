<?php namespace Lego\DSL;

/**
 * Interface MatcherInterface.
 *
 * @package Lego\DSL
 */
interface MatcherInterface
{
    /**
     * Sets and gets the matcher ID.
     *
     * @param null|int $id
     *
     * @return int|MatcherInterface
     */
    public function id($id);

    /**
     * Sets and gets the matcher expression.
     *
     * @param null|string $expr
     *
     * @return string|MatcherInterface
     */
    public function expr($expr);

    /**
     * Sets and gets the matcher callback.
     *
     * @param null|\Closure $callback
     *
     * @return \Closure|MatcherInterface
     */
    public function callback(\Closure $callback);
}
