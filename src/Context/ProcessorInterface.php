<?php namespace BEM\DSL\Context;

interface ProcessorInterface
{
    /**
     * @param mixed $arr
     *
     * @return mixed
     */
    public function process($arr);
}
