<?php namespace BEM\DSL\HTML;

interface GeneratorInterface
{
    /**
     * @param mixed $arr
     *
     * @return string
     */
    public function generate($arr);
}
