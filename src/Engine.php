<?php namespace BEM\DSL;

use BEM\DSL\Context\ProcessorInterface;
use BEM\DSL\HTML\Generator;
use BEM\DSL\Match\CollectionInterface;
use BEM\DSL\Match\LoaderInterface;
use Closure;

class Engine implements EngineInterface
{
    protected $loader;
    protected $collection;
    protected $processor;
    protected $generator;

    public function __construct(
        LoaderInterface $loader,
        CollectionInterface $collection,
        ProcessorInterface $processor,
        Generator $generator
    ) {
        $this->loader     = $loader;
        $this->collection = $collection;
        $this->processor  = $processor;
        $this->generator  = $generator;
    }

    public function setDirectories(array $directories)
    {
        $this->loader->setDirectories($directories);

        return $this;
    }

    public function setDirectory($directory)
    {
        $this->loader->setDirectory($directory);

        return $this;
    }

    public function match($expression, Closure $closure)
    {
        $this->collection->add($expression, $closure);

        return $this;
    }

    public function process($arr)
    {
        return $this->processor->process($arr);
    }

    public function html($arr)
    {
        return $this->generator->generate($arr);
    }

    public function apply($arr)
    {
        return $this->html($this->process($arr));
    }
}
