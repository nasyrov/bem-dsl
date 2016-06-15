<?php namespace Lego\DSL;

/**
 * Class MatcherCompiler.
 * @package Lego\DSL
 */
class MatcherCompiler
{
    /**
     * MatcherCollection instance.
     * @var MatcherCollectionInterface
     */
    protected $matcherCollection;

    /**
     * Create new MatcherCompiler instance
     *
     * @param MatcherCollectionInterface $matcherCollection
     */
    public function __construct(MatcherCollectionInterface $matcherCollection)
    {
        $this->matcherCollection = $matcherCollection;
    }

    public function compile()
    {
        $declarations = $this->generateDeclarations();

        $eval[] = 'return function(array $closures) {';
        $eval[] = 'return function(Lego\DSL\ContextInterface $context) use ($closures) {';
        $eval[] = 'switch ($context->block()) {';

        $declarationsByBlock = $this->groupDeclarationsBy($declarations, 'block');
        foreach ($declarationsByBlock as $block => $blockDeclarations) {
            $eval[] = sprintf('case "%s":', $block);
            $eval[] = 'switch ($context->element()) {';

            $declarationsByElem = $this->groupDeclarationsBy($blockDeclarations, 'elem');
            foreach ($declarationsByElem as $elem => $elemDeclarations) {
                $eval[] = '__no_value__' === $elem ? 'default:' : sprintf('case "%s":', $elem);

                foreach ($elemDeclarations as $elemDeclaration) {
                    $conditions = [sprintf('!$context->matchers(%d)', $elemDeclaration['index'])];

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
                        $eval[] = sprintf('$context->matchers(%d, true);', $elemDeclaration['index']);
                        $eval[] = sprintf('return $closures[%d]($context);', $elemDeclaration['index']);
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

        return $constructor($this->matcherCollection->closures());
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

    protected function generateDeclarations()
    {
        $declarations = [];

        foreach ($this->matcherCollection->expressions() as $index => $expression) {
            $declarations[] = array_merge([
                'index' => $index,
            ], $this->extractBemNotation($expression));
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
