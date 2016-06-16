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
     * @param EngineInterface $engine
     */
    public function __construct(EngineInterface $engine);

    /**
     * Loads matchers under a specific directory path.
     *
     * @param string|array $path
     *
     * @return MatcherLoaderInterface
     */
    public function load($path);
}
