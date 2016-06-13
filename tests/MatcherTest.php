<?php namespace Lego\DSL;

class MatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MatcherInterface
     */
    protected $matcher;

    public function setUp()
    {
        $this->matcher = new Matcher;
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Matcher::class, $this->matcher);
    }

    public function testExpression()
    {
        $value = 'expression';

        $this->assertInstanceOf(Matcher::class, $this->matcher->expression($value));
        $this->assertEquals($this->matcher->expression(), $value);
    }

    public function testClosure()
    {
    }
}
