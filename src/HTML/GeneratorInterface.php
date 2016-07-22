<?php namespace BEM\DSL\HTML;

interface HTMLGeneratorInterface
{
    /**
     * @param mixed $arr
     *
     * @return string
     */
    public function generate($arr);
}
