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
        $this->matcherLoader     = new MatcherLoader($this);
        $this->matcherCollection = new MatcherCollection;
        $this->matcherCompiler   = new MatcherCompiler($this->matcherCollection);
    }

    public function addMatcherDirectory($path)
    {
        $this->matcherLoader->load($path);

        return $this;
    }

    public function registerMatcher($expression, Closure $closure)
    {
        $this->matcherCollection->add($expression, $closure);

        //$this->matcherCompiler->reset();

        return $this;
    }

    public function render($context)
    {
        return $this->stringify($this->process($context));
    }

    protected function process($context)
    {
        $compiledMatchers = $this->matcherCompiler->compile();
        $contextProcessor = new ContextProcessor($compiledMatchers);

        return $contextProcessor->process($context);
    }

    protected function stringify($context)
    {
        if (is_scalar($context)) {
            return (string)$context;
        } elseif ($context instanceof ContextInterface) {
            return new Element($context);
        } elseif (is_array($context)) {
            return join('', array_map(function ($context) {
                return $this->stringify($context);
            }, $context));
        }

        throw new \LogicException(sprintf(
            'Context "%s" type cannot be rendered.',
            gettype($context)
        ));
    }
}
