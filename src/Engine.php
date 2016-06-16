<?php namespace Lego\DSL;

use Closure;

/**
 * Class Engine.
 * @package Lego\DSL
 */
class Engine implements EngineInterface
{
    /**
     * MatcherLoaderInterface instance.
     * @var MatcherLoaderInterface
     */
    protected $matcherLoader;
    /**
     * MatcherCollectionInterface instance.
     * @var MatcherCollectionInterface
     */
    protected $matcherCollection;
    /**
     * MatcherCompiler instance.
     * @var MatcherCompiler
     */
    protected $matcherCompiler;

    /**
     * Creates new Engine instance.
     */
    public function __construct()
    {
        $this->matcherCollection = new MatcherCollection;
        $this->matcherLoader     = new MatcherLoader($this);
        $this->matcherCompiler   = new MatcherCompiler($this->matcherCollection);
    }

    public function addDirectory($path)
    {
        $this->matcherLoader->load($path);

        return $this;
    }

    public function registerMatcher($expression, Closure $closure)
    {
        $this->matcherCollection->add($expression, $closure);

        return $this;
    }

    public function render($context)
    {
        if (is_scalar($context)) {
            return (string)$context;
        } elseif ($context instanceof ContextInterface) {
            return new Element($context);
        } elseif (is_array($context)) {
            return join('', array_map(function ($context) {
                return $this->render($context);
            }, $context));
        }

        throw new \LogicException(sprintf(
            'Context "%s" type cannot be rendered.',
            gettype($context)
        ));
    }
}
