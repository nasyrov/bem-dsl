<?php namespace Lego\DSL;

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

    public function expressions()
    {
        return array_keys($this->matchers);
    }

    public function closures()
    {
        return array_values($this->matchers);
    }
}
