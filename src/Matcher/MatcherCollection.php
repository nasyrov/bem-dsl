<?php namespace Lego\DSL\Matcher;

use Closure;
use LogicException;

/**
 * Class MatcherCollection.
 * @package Lego\DSL\Matcher
 */
class MatcherCollection implements MatcherCollectionInterface
{
    /**
     * @var array
     */
    protected $matchers = [];

    public function add($expression, Closure $closure)
    {
        if (is_array($expression)) {
            foreach ($expression as $value) {
                $this->add($value, $closure);
            }
        } elseif (isset($this->matchers[$expression])) {
            throw new LogicException(sprintf('The "%s" expression is already registred.', $expression));
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
