<?php namespace Lego\DSL;

/**
 * Class Matcher.
 *
 * @package Lego\DSL
 */
class Matcher implements MatcherInterface
{
    /**
     * Matcher index.
     *
     * @var int
     */
    protected static $index = 0;

    /**
     * Matcher ID.
     *
     * @var int
     */
    protected $id;

    /**
     * Matcher expression.
     *
     * @var string
     */
    protected $expr;

    /**
     * Matcher callback.
     *
     * @var \Closure
     */
    protected $callback;

    /**
     * Creates new Matcher instance.
     *
     * @param string $expr
     * @param \Closure $callback
     */
    public function __construct($expr, \Closure $callback)
    {
        $this->id(++static::$index)
             ->expr($expr)
             ->callback($callback);
    }

    public function id($id = null)
    {
        if (null === $id) {
            return $this->id;
        }

        $this->id = $id;

        return $this;
    }

    public function expr($expr = null)
    {
        if (null === $expr) {
            return $this->expr;
        }

        $this->expr = $expr;

        return $this;
    }

    public function callback(\Closure $callback = null)
    {
        if (null === $callback) {
            return $this->callback;
        }

        $reflection = new \ReflectionFunction($callback);
        $parameters = $reflection->getParameters();

        if (!isset($parameters[0]) || !$parameters[0]->getClass()->isInterface()) {
            throw new \Exception('Callback argument must be defined as ContextInterface');
        }

        $this->callback = $callback;

        return $this;
    }
}
