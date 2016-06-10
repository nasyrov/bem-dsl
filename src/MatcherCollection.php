<?php namespace Lego\DSL;

use ArrayIterator;
use Closure;

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

    public function add($expression, Closure $closure)
    {
        $this->matchers[] = new Matcher($expression, $closure);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->matchers);
    }
}
