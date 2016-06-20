<?php namespace Lego\DSL\Matcher;

interface MatcherCompilerInterface
{
    public function __construct(MatcherCollectionInterface $matcherCollection);

    public function compile();
}
