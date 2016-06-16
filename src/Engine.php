<?php namespace Lego\DSL;

use Closure;

/**
 * Class Engine.
 * @package Lego\DSL
 */
class Engine
{
    /**
     * DirectoryCollection instance.
     * @var DirectoryCollectionInterface
     */
    protected $directoryCollection;
    /**
     * MatcherCollection instance.
     * @var MatcherCollectionInterface
     */
    protected $matcherCollection;

    /**
     * Creates new Engine instance.
     */
    public function __construct()
    {
        $this->directoryCollection = new DirectoryCollection;
        $this->matcherCollection   = new MatcherCollection;
    }

    /**
     * Add directory.
     *
     * @param string|array $path
     *
     * @return $this
     */
    public function addDirectory($path)
    {
        $this->directoryCollection->add($path);

        return $this;
    }

    /**
     * Registers new matcher
     *
     * @param string|array $expression
     * @param Closure $closure
     *
     * @return Engine
     */
    public function registerMatcher($expression, Closure $closure)
    {
        $this->matcherCollection->add($expression, $closure);

        // reset compiled matchers
        $this->compiledMatchers = null;

        return $this;
    }

    public function render($context)
    {
        if ($context instanceof ContextInterface) {
            return new Element($context);
        } elseif (is_array($context)) {
            return join('', array_map(function ($context) {
                return $this->render($context);
            }, $context));
        }

        return (string)$context;
    }
}
