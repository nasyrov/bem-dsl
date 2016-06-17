<?php namespace Lego\DSL;

/**
 * Interface ContextProcessorInterface.
 * @package Lego\DSL
 */
interface ContextProcessorInterface
{
    /**
     * Creates new ContextProcessorInterface instance.
     *
     * @param MatcherCompilerInterface $matcherCompiler
     */
    public function __construct(MatcherCompilerInterface $matcherCompiler);

    /**
     * Processes given context.
     *
     * @param mixed $context
     *
     * @return array
     */
    public function process($context);
}
