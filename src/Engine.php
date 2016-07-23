<?php namespace BEM\DSL;

use BEM\DSL\Context\ProcessorInterface;
use BEM\DSL\HTML\GeneratorInterface;
use BEM\DSL\Match\CollectionInterface;
use BEM\DSL\Match\LoaderInterface;
use Closure;

class Engine implements EngineInterface
{
    protected $loader;
    protected $collection;
    protected $processor;
    protected $generator;

    /**
     * Engine constructor.
     *
     * @param LoaderInterface $loader
     * @param CollectionInterface $collection
     * @param ProcessorInterface $processor
     * @param GeneratorInterface $generator
     */
    public function __construct(
        LoaderInterface $loader,
        CollectionInterface $collection,
        ProcessorInterface $processor,
        GeneratorInterface $generator
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

    public function process($bemArr)
    {
        return $this->processor->process($bemArr);
    }

    public function toHtml($bemArr)
    {
        return $this->generator->generate($bemArr);
    }

    public function apply($bemArr)
    {
        return $this->toHtml($this->process($bemArr));
    }
}
