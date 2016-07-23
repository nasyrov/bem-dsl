<?php namespace BEM\DSL\Context;

use Closure;

interface ProcessorInterface
{
    /**
     * @return Closure
     */
    public function getMatcher();

    /**
     * @param mixed $bemArr
     *
     * @return mixed
     */
    public function process($bemArr);
}
