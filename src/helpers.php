<?php namespace Lego\DSL;

function match($expr, $func)
{
    return Engine::instance()->match($expr, $func);
}

function render($context)
{
    return Engine::instance()->render($context);
}

function tag($tag, array $params = [])
{
    return new Entity(array_merge(['tag' => $tag], $params));
}

function block($block, array $params = [])
{
    return new Entity(array_merge(['block' => $block], $params));
}

function elem($elem, array $params = [])
{
    return new Entity(array_merge(['elem' => $elem], $params));
}
