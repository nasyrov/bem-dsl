<?php namespace Lego\DSL;

class ContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContextInterface
     */
    protected $context;

    public function setUp()
    {
        $this->context = new Context;
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Context::class, $this->context);
    }

    public function testBlock()
    {
        $value = 'block';

        $this->assertInstanceOf(Context::class, $this->context->block($value));
        $this->assertEquals($this->context->block(), $value);
    }

    public function testElement()
    {
        $value = 'element';

        $this->assertInstanceOf(Context::class, $this->context->element($value));
        $this->assertEquals($this->context->element(), $value);
    }

    public function testMixes()
    {
    }

    public function testTag()
    {
        $value = 'tag';

        $this->assertInstanceOf(Context::class, $this->context->tag($value));
        $this->assertEquals($this->context->tag(), $value);
    }

    public function testClasses()
    {
        $this->assertInstanceOf(Context::class, $this->context->classes(['class-1']));
        $this->assertInstanceOf(Context::class, $this->context->classes(['class-2', 'class-3']));
        $this->assertEquals($this->context->classes(), ['class-1', 'class-2', 'class-3']);
    }

    public function testModifiers()
    {
        $this->assertInstanceOf(Context::class, $this->context->modifiers('modifier-1', 'modifier-1-value'));
        $this->assertInstanceOf(Context::class, $this->context->modifiers(['modifier-2' => 'modifier-2-value']));
        $this->assertEquals($this->context->modifiers(), [
            'modifier-1' => 'modifier-1-value',
            'modifier-2' => 'modifier-2-value'
        ]);
    }

    public function testAttributes()
    {
        $this->assertInstanceOf(Context::class, $this->context->attributes('attribute-1', 'attribute-1-value'));
        $this->assertInstanceOf(Context::class, $this->context->attributes(['attribute-2' => 'attribute-2-value']));
        $this->assertEquals($this->context->attributes(), [
            'attribute-1' => 'attribute-1-value',
            'attribute-2' => 'attribute-2-value'
        ]);
    }

    public function testBem()
    {
        $this->assertInstanceOf(Context::class, $this->context->bem(false));
        $this->assertEquals($this->context->bem(), false);
    }

    public function testJs()
    {
    }

    public function testContent()
    {
        $this->assertInstanceOf(Context::class, $this->context->content('content-1', 'content-2'));
        $this->assertEquals($this->context->content(), ['content-1', 'content-2']);
    }

    public function testMatchers()
    {
    }
}
