<?php namespace Lego\DSL\Context;

use Lego\DSL\Matcher\MatcherCompilerInterface;
use Lego\DSL\Node\NodeFactory;

class ContextProcessor
{
    protected $matcherCompiler;

    public function __construct(MatcherCompilerInterface $matcherCompiler)
    {
        $this->matcherCompiler = $matcherCompiler;
    }

    public function process(ContextInterface $context)
    {
        $result = [$context];

        $nodes[] = NodeFactory::create()
                              ->block($context->block())
                              ->content($context)
                              ->result($result);

        $compiledMatcher = $this->matcherCompiler->compile();

        /**
         * @var $node \Lego\DSL\Node\NodeInterface
         */
        while ($node = array_pop($nodes)) {
            $nodeBlock   = $node->block();
            $nodeContent = $node->content();

            if (is_array($nodeContent)) {
                $this->сhildren($nodes, $nodeContent, $nodeBlock);

                $result[$node->index()] = $nodeContent;

                continue;
            } elseif ($nodeContent instanceof ContextInterface) {
                if ($nodeContent->element()) {
                    $nodeBlock = $nodeContent->block() ?: $nodeBlock;

                    $nodeContent->block($nodeBlock);
                } elseif ($nodeContent->block()) {
                    $nodeBlock = $nodeContent->block();
                }

                $nodeContent->node($node);

                $compiledResult = $compiledMatcher($nodeContent);
                if (null !== $compiledResult) {
                    $nodes[] = $node->block($nodeBlock)->content($compiledResult);

                    continue;
                }

                if ($nodeContent->content()) {
                    if (is_array($nodeContent->content())) {
                        $this->сhildren($nodes, $nodeContent->content(), $nodeBlock);

                        continue;
                    }

                    $nodes[] = NodeFactory::create()
                                          ->index('content')
                                          ->block($nodeBlock)
                                          ->content($nodeContent->content())
                                          ->result($nodeContent);
                }
            }
        }

        return $result[0];
    }

    protected function сhildren(array &$nodes, array $children, $block)
    {
        foreach ($children as $index => $child) {
            if (!$child instanceof ContextInterface) {
                continue;
            }

            $nodes[] = NodeFactory::create()
                                  ->index($index)
                                  ->block($block)
                                  ->position($index + 1)
                                  ->content($child)
                                  ->result($children);
        }
    }
}
