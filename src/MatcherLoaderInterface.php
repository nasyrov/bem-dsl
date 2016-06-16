<?php namespace Lego\DSL;

/**
 * Interface MatcherLoaderInterface.
 * @package Lego\DSL
 */
interface MatcherLoaderInterface
{
    /**
     * Creates new MatcherLoaderInterface instance.
     *
     * @param Engine $engine
     */
    public function __construct(Engine $engine);

    /**
     * Adds new directory.
     *
     * @param string|array $path
     *
     * @return MatcherLoaderInterface
     */
    public function addDirectory($path);
}
