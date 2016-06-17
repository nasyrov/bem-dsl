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
     * MatcherCompilerInterface instance.
     * @var MatcherCompilerInterface
     */
    protected $matcherCompiler;
    /**
     * ContextProcessorInterface instance.
     * @var ContextProcessorInterface
     */
    protected $contextProcessor;

    /**
     * Creates new Engine instance.
     */
    public function __construct()
    {
        $this->matcherCollection = new MatcherCollection;
        $this->matcherLoader     = new MatcherLoader($this);
        $this->matcherCompiler   = new MatcherCompiler($this->matcherCollection);
        $this->contextProcessor  = new ContextProcessor($this->matcherCompiler);
    }

    public function directory($path)
    {
        $this->matcherLoader->load($path);

        return $this;
    }

    public function matcher($expression, Closure $closure)
    {
        $this->matcherCollection->add($expression, $closure);

        return $this;
    }

    public function render($context)
    {
        return $this->stringify($this->contextProcessor->process($context));
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
