<?php namespace BEM\DSL\HTML;

interface GeneratorInterface
{
    /**
     * @param mixed $bemArr
     *
     * @return string
     */
    public function generate($bemArr);
}
