<?php namespace BEM\DSL;

use BEM\DSL\Context\Processor;
use BEM\DSL\HTML\Generator;
use BEM\DSL\Match\Collection;
use BEM\DSL\Match\Compiler;
use BEM\DSL\Match\Loader;

class EngineTest extends \PHPUnit_Framework_TestCase
{
    protected $engine;

    public function setUp()
    {
        $collection = new Collection;
        $loader     = new Loader($collection);
        $compiler   = new Compiler($collection);

        $processor = new Processor($compiler);

        $generator = new Generator;

        $this->engine = new Engine($loader, $collection, $processor, $generator);
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(EngineInterface::class, $this->engine);
    }
}
