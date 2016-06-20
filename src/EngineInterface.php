<?php namespace Lego\DSL;

use Closure;
use Lego\DSL\Context\ContextInterface;

interface EngineInterface
{
    /**
     * Returns the EngineInterface instance of this class.
     *
     * @return EngineInterface
     */
    public static function instance();

    public function matcher($expression, Closure $closure);

    public function render(ContextInterface $context);
}
