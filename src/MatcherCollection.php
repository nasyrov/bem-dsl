<?php namespace Lego\DSL;

use ArrayIterator;

/**
 * Class MatcherCollection.
 *
 * @package Lego\DSL
 */
class MatcherCollection implements MatcherCollectionInterface
{
    /**
     * Collection of matchers.
     *
     * @var MatcherInterface[]
     */
    protected $matchers;

    /**
     * Creates new MatcherCollection instance.
     *
     * @param array $matchers
     */
    public function __construct(array $matchers = [])
    {
        $this->matchers = $matchers;
    }

    public function count()
    {
        return count($this->matchers);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->matchers);
    }

    public function offsetExists($expr)
    {
        return isset($this->matchers[$expr]);
    }

    public function offsetGet($expr)
    {
        if (!$this->offsetExists($expr)) {
            throw new \LogicException(
                sprintf('The matcher expression "%s" wan not found.', $expr)
            );
        }

        return $this->matchers[$expr];
    }

    public function offsetSet($expr, $callback)
    {
        if ($this->offsetExists($expr)) {
            throw new \LogicException(
                sprintf('The matcher expression "%s" is already registered.', $expr)
            );
        }

        $this->matchers[$expr] = new Matcher($expr, $callback);

        return $this;
    }

    public function offsetUnset($expr)
    {
        if (!$this->offsetExists($expr)) {
            throw new \LogicException(
                sprintf('The matcher expression "%s" wan not found.', $expr)
            );
        }

        unset($this->matchers[$expr]);

        return $this;
    }
}
