<?php namespace Lego\DSL;

use ArrayIterator;
use Closure;

class MatcherCollection implements MatcherCollectionInterface
{
    /**
     * Collection of matchers.
     * @var MatcherInterface
     */
    protected $matchers = [];

    /**
     * @param string|array $expression
     * @param Closure $closure
     *
     * @return MatcherCollectionInterface
     */
    public function add($expression, Closure $closure)
    {
        if (is_array($expression)) {
            foreach ($expression as $value) {
                $this->add($value, $closure);
            }
        } elseif (array_key_exists($expression, $this->matchers)) {
            throw new \LogicException(sprintf('The "%s" expression is already registered.', $expression));
        } else {
            $this->matchers[$expression] = $closure;
        }

        return $this;
    }

    public function getExpressions()
    {
        return array_keys($this->matchers);
    }

    public function getClosures()
    {
        return array_values($this->matchers);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->matchers);
    }
}
