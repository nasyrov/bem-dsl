<?php namespace Lego\DSL;

use Closure;

/**
 * Interface MatcherCompilerInterface.
 * @package Lego\DSL
 */
interface MatcherCompilerInterface
{
    /**
     * Create new MatcherCompiler instance
     *
     * @param MatcherCollectionInterface $matcherCollection
     */
    public function __construct(MatcherCollectionInterface $matcherCollection);

    /**
     * Compiles all the matchers.
     *
     * @return Closure
     */
    public function compile();
}
