<?php namespace Lego\DSL;

use Closure;
use Lego\DSL\Context\Context;
use Lego\DSL\Context\ContextInterface;

function matcher($expression, Closure $closure)
{
    return Engine::instance()->matcher($expression, $closure);
}

function render(ContextInterface $context)
{
    return Engine::instance()->render($context);
}

function tag($name)
{
    return (new Context)->tag($name);
}

function block($name)
{
    return (new Context)->block($name);
}

function element($name)
{
    return (new Context)->element($name);
}
