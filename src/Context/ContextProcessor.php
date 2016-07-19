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

        $nodes[] = [
            'index'   => 0,
            'block'   => $context->block(),
            'context' => $context,
        ];

        $compiledMatcher = $this->matcherCompiler->compile();

        while ($node = array_shift($nodes)) {
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
                    ];
                }

                $result[$node['index']] = $nodeContext;

                continue;
            } elseif ($nodeContext instanceof ContextInterface) {
                if ($nodeContext->element()) {
                    $nodeBlock = $nodeContext->block() ?: $nodeBlock;
                    $nodeContext->block($nodeBlock);
                } elseif ($nodeContext->block()) {
                    $nodeBlock = $nodeContext->block();
                }

                $compiledResult = $compiledMatcher($nodeContext);
                if (null !== $compiledResult) {
                    echo $node['block'], ' : ', $nodeBlock, "\n";
                    $node['block']   = $nodeBlock;
                    $node['context'] = $compiledResult;

                    $nodes[] = $node;

                    continue;
                }

                if ($nodeContext->content()) {
                    if (is_array($nodeContext->content())) {
                        foreach ($nodeContext as $index => $child) {
                            if (!$child instanceof ContextInterface) {
                                continue;
                            }

                            $nodes[] = [
                                'index'   => $index,
                                'block'   => $nodeBlock,
                                'context' => $child,
                            ];
                        }

                        continue;
                    }

                    $nodes[] = [
                        'index'   => 'content',
                        'block'   => $nodeBlock,
                        'context' => $nodeContext->content(),
                    ];
                }
            }
        }

        return $result[0];
    }
}
