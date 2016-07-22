<?php namespace BEM\DSL\Match;

use Closure;
use LogicException;

class Collection implements CollectionInterface
{
    protected $matches = [];

    public function add($expression, Closure $closure)
    {
        if (is_array($expression)) {
            foreach ($expression as $value) {
                $this->add($value, $closure);
            }
        } elseif (array_key_exists($expression, $this->matches)) {
            throw new LogicException(sprintf('The "%s" expression is already registered.', $expression));
        } else {
            $this->matches[$expression] = $closure;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function expressions()
    {
        return array_keys($this->matches);
    }

    /**
     * @return array
     */
    public function closures()
    {
        return array_values($this->matches);
    }
}
