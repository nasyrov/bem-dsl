<?php namespace BEM\DSL;

use BEM\DSL\Context\ProcessorInterface;
use BEM\DSL\HTML\Generator;
use BEM\DSL\Match\CollectionInterface;
use BEM\DSL\Match\LoaderInterface;
use Closure;

class Engine implements EngineInterface
{
    protected $matchLoader;
    protected $matchCollection;
    protected $matchCompiler;
    protected $contextProcessor;
    protected $htmlGenerator;

    public function __construct(
        LoaderInterface $matchLoader,
        CollectionInterface $matchCollection,
        ProcessorInterface $contextProcessor,
        Generator $htmlGenerator
    ) {
        $this->matchLoader      = $matchLoader;
        $this->matchCollection  = $matchCollection;
        $this->contextProcessor = $contextProcessor;
        $this->htmlGenerator    = $htmlGenerator;
    }

    public function setDirectories(array $directories)
    {
        $this->matchLoader->setDirectories($directories);

        return $this;
    }

    public function setDirectory($directory)
    {
        $this->matchLoader->setDirectory($directory);

        return $this;
    }

    public function match($expression, Closure $closure)
    {
        $this->matchCollection->add($expression, $closure);

        return $this;
    }

    public function process($arr)
    {
        return $this->contextProcessor->process($arr);
    }

    public function html($arr)
    {
        return $this->htmlGenerator->generate($arr);
    }

    public function apply($arr)
    {
        return $this->html($this->process($arr));
    }
}
