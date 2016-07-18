<?php namespace Lego\DSL\Matcher;

class MatcherCompiler implements MatcherCompilerInterface
{
    protected $matcherCollection;

    public function __construct(MatcherCollectionInterface $matcherCollection)
    {
        $this->matcherCollection = $matcherCollection;
    }

    public function compile()
    {
        $declarations = $this->declarations();

        $eval[] = 'return function(array $closures) {';
        $eval[] = 'return function(Lego\DSL\Context\ContextInterface $context) use ($closures) {';
        $eval[] = 'switch ($context->block()) {';

        foreach ($this->groupBy($declarations, 'block') as $block => $blockDeclarations) {
            $eval[] = sprintf('case "%s":', $block);
            $eval[] = 'switch ($context->element()) {';

            foreach ($this->groupBy($blockDeclarations, 'element') as $element => $elementDeclarations) {
                $eval[] = '__no_value__' === $element ? 'default:' : sprintf('case "%s":', $element);

                foreach ($elementDeclarations as $elementDeclaration) {
                    $conditions = [sprintf('!$context->matchers(%d)', $elementDeclaration['index'])];

                    $pairs = [
                        'blockModifier'   => 'blockModifierValue',
                        'elementModifier' => 'elementModifierValue',
                    ];
                    foreach ($pairs as $modifier => $value) {
                        if (!isset($elementDeclaration[$modifier])) {
                            continue;
                        }

                        $conditions[] = sprintf(
                            '$context.modifiers() && %s === $context.modifiers("%s")',
                            $elementDeclaration[$modifier],
                            true === $elementDeclaration[$value] ?
                                'true' :
                                sprintf('"%s"', $elementDeclaration[$value])
                        );
                    }
                }

                $eval[] = sprintf('if (%s) {', implode(' && ', $conditions));
                $eval[] = sprintf('$context->matchers(%d, true);', $elementDeclaration['index']);
                $eval[] = sprintf('return $closures[%d]($context);', $elementDeclaration['index']);
                $eval[] = '}';
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

    protected function declarations()
    {
        $declarations = [];
        foreach ($this->matcherCollection->expressions() as $index => $expression) {
            $declarations[] = array_merge(
                ['index' => $index],
                $this->extractBemNotation($expression)
            );
        }

        return $declarations;
    }

    protected function extractBemNotation($expression)
    {
        list($blockBits, $elementBits) = explode('__', $expression . "__\1");

        list($block, $blockModifier, $blockModifierValue) = $this->extractBemBits($blockBits);
        if ("\1" !== $elementBits) {
            list($element, $elementModifier, $elementModifierValue) = $this->extractBemBits($elementBits);
        }

        return compact(
            'block',
            'blockModifier',
            'blockModifierValue',
            'element',
            'elementModifier',
            'elementModifierValue'
        );
    }

    protected function extractBemBits($bits)
    {
        list($name, $modifier, $value) = explode('_', $bits . "_\1_\1");

        $modifier = "\1" === $modifier ? null : $modifier;
        $value    = $modifier ? ("\1" === $value ? true : $value) : null;

        return [$name, $modifier, $value];
    }

    protected function groupBy(array $declarations, $group)
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
