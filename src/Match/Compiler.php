<?php namespace BEM\DSL\Match;

class Compiler implements CompilerInterface
{
    protected $matchCollection;

    public function __construct(CollectionInterface $matchCollection)
    {
        $this->matchCollection = $matchCollection;
    }

    public function compile()
    {
        $closure = eval($this->precompile());

        return $closure($this->matchCollection->closures());
    }

    protected function precompile()
    {
        $declarations = $this->declarations();

        $code[] = 'return function(array $closures) {';
        $code[] = 'return function($ctx, $arr) use ($closures) {';
        $code[] = 'switch ($arr->block) {';

        foreach ($this->groupBy($declarations, 'block') as $block => $blockDeclarations) {
            $code[] = sprintf('case "%s":', $block);
            $code[] = 'switch ($arr->elem) {';

            foreach ($this->groupBy($blockDeclarations, 'elem') as $element => $elementDeclarations) {
                $code[] = '__no_value__' === $element ? 'default:' : sprintf('case "%s":', $element);

                foreach ($elementDeclarations as $elementDeclaration) {
                    $conditions = [sprintf('!$arr->_closure%d', $elementDeclaration['index'])];

                    $pairs = [
                        'blockMod' => 'blockModVal',
                        'elemMod'  => 'elemModVal',
                    ];
                    foreach ($pairs as $modifier => $value) {
                        if (!isset($elementDeclaration[$modifier])) {
                            continue;
                        }

                        $conditions[] = sprintf(
                            '$arr->mods && %s === $arr->mods["%s"]',
                            $elementDeclaration[$modifier],
                            true === $elementDeclaration[$value] ? 'true' : sprintf('"%s"', $elementDeclaration[$value])
                        );
                    }
                }

                $code[] = sprintf('if (%s) {', implode(' && ', $conditions));
                $code[] = sprintf('$arr->_closure%d = true;', $elementDeclaration['index']);
                $code[] = sprintf('return $closures[%d]($ctx, $arr);', $elementDeclaration['index']);
                $code[] = '}';
            }

            $code[] = '}';
            $code[] = 'break;';
        }

        $code[] = '}';
        $code[] = '};';
        $code[] = '};';

        return join("\n", $code);
    }

    /**
     * @return array
     */
    protected function declarations()
    {
        $declarations = [];
        foreach ($this->matchCollection->expressions() as $index => $expression) {
            $declarations[] = ['index' => $index] + $this->extractNotations($expression);
        }

        return $declarations;
    }

    protected function extractNotations($expression)
    {
        if (false !== strpos($expression, $this->optDelimElem)) {
            $exprBits           = explode($this->optDelimElem, $expression);
            $blockExprBits      = explode($this->optDelimMod, $exprBits[0]);
            $notations['block'] = $blockExprBits[0];
            if (sizeof($blockExprBits) > 2) {
                $notations['blockMod']    = $blockExprBits[1];
                $notations['blockModVal'] = $blockExprBits[2] ?: true;
            }
            $elemExprBits      = explode($this->optDelimMod, $exprBits[1]);
            $notations['elem'] = $elemExprBits[0];
            if (sizeof($exprBits) > 2) {
                $notations['elemMod']    = $elemExprBits[1];
                $notations['elemModVal'] = $elemExprBits[2] ?: true;
            }
        } else {
            $exprBits           = explode($this->optDelimMod, $expression);
            $notations['block'] = $exprBits[0];
            if (sizeof($exprBits) > 2) {
                $notations['blockMod']    = $exprBits[1];
                $notations['blockModVal'] = $exprBits[2] ?: true;
            }
        }

        return $notations;
    }

    /**
     * @param array $declarations
     * @param string $group
     *
     * @return array
     */
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
