<?php namespace Lego\DSL;

use Closure;

/**
 * Class Engine
 *
 * @package Lego\DSL
 */
class Engine
{
    /**
     * Collection of matchers.
     *
     * @var MatcherInterface[]
     */
    protected $matchers;

    /**
     * Compiled matchers.
     *
     * @var Closure
     */
    protected $compiledMatchers;

    /**
     * Registers new matcher
     *
     * @param string|array $expression
     * @param Closure $closure
     *
     * @return Engine
     */
    public function registerMatcher($expression, Closure $closure)
    {
        if (is_array($expression)) {
            return array_map(function ($expression) use ($closure) {
                return $this->registerMatcher($expression, $closure);
            }, $expression);
        }

        $this->matchers[] = new Matcher($expression, $closure);

        // reset compiled matchers
        $this->compiledMatchers = null;

        return $this;
    }

    /**
     * Render.
     *
     * @param array ...$bricks
     *
     * @return array
     */
    public function render(...$bricks)
    {
        return $this->process($bricks);
    }

    protected function process(array $bricks)
    {
        $compiledMatchers = $this->getCompiledMatchers();

        $position = 0;

        $nodes[] = (new Node)
            ->index(0)
            ->bricks($bricks);

        /**
         * @var $node NodeInterface
         */
        while ($node = array_shift($nodes)) {
            if (is_array($node->bricks())) {
                foreach ($node->bricks() as $index => $brick) {
                    if ($brick instanceof ContextInterface) {
                        $nodes[] = (new Node)
                            ->index($index)
                            ->position(++$position)
                            ->bricks($node->bricks())
                            ->parent($node);
                    }
                }
            } elseif ($node->bricks() instanceof ContextInterface) {

            }
        }

        return $nodes;
    }

    protected function getCompiledMatchers()
    {
        if (null === $this->compiledMatchers) {
            $this->compiledMatchers = (new MatcherCompiler($this->matchers))->compile();
        }

        return $this->compiledMatchers;
    }
}
