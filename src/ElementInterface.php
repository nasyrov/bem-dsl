<?php namespace Lego\DSL;

interface ElementInterface
{
    /**
     * Creates new ElementInterface instance.
     *
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context);

    public function __toString();

    /**
     * Renders given context.
     *
     * @return string
     */
    public function render();
}
