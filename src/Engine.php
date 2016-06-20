<?php namespace Lego\DSL;

use Closure;
use Lego\DSL\Context\ContextInterface;
use Lego\DSL\Context\ContextProcessor;
use Lego\DSL\Context\ContextRender;
use Lego\DSL\Matcher\MatcherCollection;
use Lego\DSL\Matcher\MatcherCompiler;
use Lego\DSL\Matcher\MatcherLoader;

class Engine implements EngineInterface
{
    /**
     * @var Engine
     */
    private static $instance;

    protected $matcherLoader;
    protected $matcherCollection;
    protected $matcherCompiler;
    protected $contextProcessor;
    protected $contextRender;

    final public static function instance()
    {
        if (null === static::$instance) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Prevents creating a new instance.
     */
    protected function __construct()
    {
        $this->matcherLoader     = new MatcherLoader;
        $this->matcherCollection = new MatcherCollection;
        $this->matcherCompiler   = new MatcherCompiler($this->matcherCollection);
        $this->contextProcessor  = new ContextProcessor($this->matcherCompiler);
        $this->contextRender     = new ContextRender;
    }

    /**
     * Prevents cloning an instance.
     */
    private function __clone()
    {
    }

    /**
     * Prevents unserializing an instance.
     */
    private function __wakeup()
    {
    }

    public function directory($directory)
    {
        $this->matcherLoader->load($directory);

        return $this;
    }

    public function matcher($expression, Closure $closure)
    {
        $this->matcherCollection->add($expression, $closure);

        return $this;
    }

    public function render(ContextInterface $context)
    {
        return $this->contextRender->render(
            $this->contextProcessor->process($context)
        );
    }
}
