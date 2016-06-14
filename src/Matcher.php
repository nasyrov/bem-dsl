<?php namespace Lego\DSL;

use Closure;
use Exception;
use ReflectionFunction;

/**
 * Class Matcher.
 * @package Lego\DSL
 */
class Matcher implements MatcherInterface
{
    /**
     * Expression.
     * @var string
     */
    protected $expr;
    /**
     * Closure.
     * @var Closure
     */
    protected $closure;

    /**
     * Creates new Matcher instance.
     *
     * @param null|string $expr
     * @param null|Closure $closure
     */
    public function __construct($expr = null, Closure $closure = null)
    {
        if ($expr) {
            $this->expression($expr);
        }

        if ($closure) {
            $this->closure($closure);
        }
    }

    public function expression($expression = null)
    {
        if (null === $expression) {
            return $this->expr;
        }

        $this->expr = $expression;

        return $this;
    }

    public function closure(Closure $closure = null)
    {
        if (null === $closure) {
            return $this->closure;
        }

        $reflection = new ReflectionFunction($closure);
        $parameters = $reflection->getParameters();

        if (!isset($parameters[0]) || !$parameters[0]->getClass()->isInterface()) {
            throw new Exception('Callback argument must be defined as ContextInterface');
        }

        $this->closure = $closure;

        return $this;
    }
}
