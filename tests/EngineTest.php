<?php namespace Lego\DSL;

class EngineTest extends \PHPUnit_Framework_TestCase
{
    protected $engine;

    public function setUp()
    {
        $this->engine = new Engine;
    }

    public function testCanCreateInstance()
    {
        $this->assertInstanceOf(Engine::class, $this->engine);
    }
}
