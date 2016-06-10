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
        $declarations = $this->getDeclarations();

        $eval[] = 'return function(Lego\DSL\MatcherCollectionInterface $matcherCollection) {';
        $eval[] = 'return function(Lego\DSL\ContextInterface $context) use ($matcherCollection) {';
        $eval[] = 'switch ($context->block()) {';

        $declarationsByBlock = $this->groupDeclarationsBy($declarations, 'block');
        foreach ($declarationsByBlock as $block => $blockDeclarations) {
            $eval[] = sprintf('case "%s":', $block);
            $eval[] = 'switch ($context->elem()) {';

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
                        $eval[] = sprintf('$closure = $matcherCollection[%d]->callback();', $elemDeclaration['index']);
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

        return $constructor($this->matcherCollection);
    }

    protected function extractBemNotation($expression)
    {
        list($blockBits, $elemBits) = explode('__', $expression . "__\1");

        list($block, $blockMod, $blockModVal) = $this->extractBemBits($blockBits);
        list($elem, $elemMod, $elemModVal) = $this->extractBemBits($elemBits);

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
         * @var $expression string
         * @var $matcher MatcherInterface
         */
        foreach ($this->matcherCollection as $expression => $matcher) {
            $declarations[] = $this->extractBemNotation($expression) + [
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
