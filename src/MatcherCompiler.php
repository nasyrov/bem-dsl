<?php namespace Lego\DSL;

/**
 * Class MatcherCompiler.
 *
 * @package Lego\DSL
 */
class MatcherCompiler
{
    /**
     * Collection of matchers.
     *
     * @var MatcherInterface[]
     */
    protected $matchers;

    /**
     * Create new MatcherCompiler instance
     *
     * @param MatcherInterface[] $matchers
     */
    public function __construct(array $matchers)
    {
        $this->matchers = $matchers;
    }

    public function compile()
    {
        $declarations = $this->getDeclarations();

        $eval[] = 'return function(array $matchers) {';
        $eval[] = 'return function(Lego\DSL\ContextInterface $context) use ($matchers) {';
        $eval[] = 'switch ($context->block()) {';

        $declarationsByBlock = $this->groupDeclarationsBy($declarations, 'block');
        foreach ($declarationsByBlock as $block => $blockDeclarations) {
            $eval[] = sprintf('case "%s":', $block);
            $eval[] = 'switch ($context->element()) {';

            $declarationsByElem = $this->groupDeclarationsBy($blockDeclarations, 'elem');
            foreach ($declarationsByElem as $elem => $elemDeclarations) {
                $eval[] = '__no_value__' === $elem ? 'default:' : sprintf('case "%s":', $elem);

                foreach ($elemDeclarations as $elemDeclaration) {
                    $conditions = [sprintf('!$context->matchers(%d)', $elemDeclaration['matcherId'])];

                    foreach (['elemMod' => 'elemModVal', 'blockMod' => 'blockModVal'] as $modKey => $modVal) {
                        if (!isset($elemDeclaration[$modKey])) {
                            continue;
                        }

                        $conditions[] = sprintf(
                            '$context.mods() && $context.mods("%s") === %s',
                            $elemDeclaration[$modKey],
                            $elemDeclaration[$modVal] === true ? 'true' : sprintf('"%s"', $elemDeclaration[$modVal])
                        );
                    }

                    if ($conditions) {
                        $eval[] = sprintf('if (%s) {', join(' && ', $conditions));
                        $eval[] = sprintf('$context->matchers(%d, true);', $elemDeclaration['matcherId']);
                        $eval[] = sprintf('$closure = $matchers[%d]->callback();', $elemDeclaration['index']);
                        $eval[] = 'return $closure($context);';
                        $eval[] = '}';
                    }
                }

                $eval[] = 'break;';
            }

            $eval[] = '}';
            $eval[] = 'break;';
        }

        $eval[] = '}';
        $eval[] = '};';
        $eval[] = '};';

        $constructor = eval(join("\n", $eval));

        return $constructor($this->matchers);
    }

    protected function extractBemNotation($expression)
    {
        list($blockBits, $elemBits) = explode('__', $expression . "__\1");

        list($block, $blockMod, $blockModVal) = $this->extractBemBits($blockBits);
        if ("\1" !== $elemBits) {
            list($elem, $elemMod, $elemModVal) = $this->extractBemBits($elemBits);
        }

        return compact('block', 'blockMod', 'blockModVal', 'elem', 'elemMod', 'elemModVal');
    }

    protected function extractBemBits($bits)
    {
        list($name, $mod, $val) = explode('_', $bits . "_\1_\1");

        $mod = $mod === "\1" ? null : $mod;
        $val = $mod ? ($val === "\1" ? true : $val) : null;

        return [$name, $mod, $val];
    }

    protected function getDeclarations()
    {
        $declarations = [];

        $index = 0;

        /**
         * @var $matcher MatcherInterface
         */
        foreach ($this->matchers as $matcher) {
            $declarations[] = $this->extractBemNotation($matcher->expr()) + [
                    'index'     => $index++,
                    'matcherId' => $matcher->id(),
                ];
        }

        return $declarations;
    }

    protected function groupDeclarationsBy(array $declarations, $group)
    {
        $result = [];

        foreach ($declarations as $declaration) {
            $value = isset($declaration[$group]) ? $declaration[$group] : '__no_value__';
            isset($result[$value]) || $result[$value] = [];
            $result[$value][] = $declaration;
        }

        return $result;
    }
}
