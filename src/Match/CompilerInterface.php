<?php namespace BEM\DSL\Match;

use Closure;

interface CompilerInterface
{
    /**
     * @return Closure
     */
    public function compile();
}
