<?php namespace Lego\DSL;

use Closure;

/**
 * Class Engine.
 * @package Lego\DSL
 */
class Engine
{
    /**
     * Compiled matchers.
     *
     * @var Closure
     */
    protected $compiledMatchers;
    /**
     * DirectoryCollection instance.
     * @var DirectoryCollectionInterface
     */
    protected $directoryCollection;
    /**
     * MatcherCollection instance.
     * @var MatcherCollectionInterface
     */
    protected $matcherCollection;

    /**
     * Creates new Engine instance.
     */
    public function __construct()
    {
        $this->directoryCollection = new DirectoryCollection;
        $this->matcherCollection   = new MatcherCollection;
    }

    /**
     * Add directory.
     *
     * @param string|array $path
     *
     * @return $this
     */
    public function addDirectory($path)
    {
        $this->directoryCollection->add($path);

        return $this;
    }

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
        $this->matcherCollection->add($expression, $closure);

        // reset compiled matchers
        $this->compiledMatchers = null;

        return $this;
    }

    public function render($context)
    {
        if ($context instanceof ContextInterface) {
            return new Element($context);
        } elseif (is_array($context)) {
            return join('', array_map(function ($context) {
                return $this->render($context);
            }, $context));
        }

        return (string)$context;
    }

    protected function process(ContextInterface $context)
    {
        $compiledMatchers = $this->getCompiledMatchers();

        $result = [$context];

        $nodes[] = [
            'index'   => 0,
            'block'   => null,
            'context' => $context,
            'result'  => $result,
        ];

        while ($node = array_pop($nodes)) {
            $nodeBlock   = $node['block'];
            $nodeContext = $node['context'];

            if (is_array($nodeContext)) {
                foreach ($nodeContext as $index => $child) {
                    if (!$child instanceof ContextInterface) {
                        continue;
                    }

                    $nodes[] = [
                        'index'   => $index,
                        'block'   => $nodeBlock,
                        'context' => $child,
                        'result'  => $nodeContext,
                    ];
                }

                $result[$node['index']] = $nodeContext;

                continue;
            }

            if ($nodeContext->element()) {
                $nodeBlock = $nodeContext->block() ?: $nodeBlock;
                $nodeContext->block($nodeBlock);
            } elseif ($nodeContext->block()) {
                $nodeBlock = $nodeContext->block();
            }

            $compiledResult = $compiledMatchers($nodeContext);
            if (null !== $compiledResult) {
                $nodeContext = $compiledResult;

                $node['block']   = $nodeBlock;
                $node['context'] = $nodeContext;

                $nodes[] = $node;

                continue;
            }

            if ($nodeContext->content()) {
                if (is_array($nodeContext->content())) {
                    foreach ($nodeContext->content() as $index => $child) {
                        if (!$child instanceof ContextInterface) {
                            continue;
                        }

                        $nodes[] = [
                            'index'   => $index,
                            'block'   => $nodeBlock,
                            'context' => $child,
                            'result'  => $nodeContext,
                        ];
                    }
                } else {
                    $nodes[] = [
                        'index'   => 'content',
                        'block'   => $nodeBlock,
                        'context' => $nodeContext->content(),
                        'result'  => $nodeContext,
                    ];
                }
            }
        }

        return $result[0];
    }

    protected function getCompiledMatchers()
    {
        if (null === $this->compiledMatchers) {
            $this->compiledMatchers = (new MatcherCompiler($this->matcherCollection))->compile();
        }

        return $this->compiledMatchers;
    }
}
