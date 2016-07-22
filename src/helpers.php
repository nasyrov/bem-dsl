<?php namespace BEM\DSL;

/**
 * @param string $tag
 * @param array $params
 *
 * @return Entity
 */
function tag($tag, array $params = [])
{
    return new Entity(['tag' => $tag] + $params);
}

/**
 * @param string $block
 * @param array $params
 *
 * @return Entity
 */
function block($block, array $params = [])
{
    return new Entity(['block' => $block] + $params);
}

/**
 * @param string $element
 * @param array $params
 *
 * @return Entity
 */
function element($element, array $params = [])
{
    return new Entity(['element' => $element] + $params);
}
