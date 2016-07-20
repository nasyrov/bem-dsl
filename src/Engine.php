<?php namespace Lego\DSL;

use Closure;
use LogicException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class Engine
{
    private static $instance;

    protected $optJsAttrName = 'onclick';
    protected $optJsAttrIsJs = true;
    protected $optJsCls = 'i-bem';
    protected $optJsElem = true;
    protected $optEscapeContent = false;
    protected $optNobaseMods = false;
    protected $optDelimElem = '__';
    protected $optDelimMod = '_';

    protected $matches = [];
    protected $lastMatchId = 0;
    protected $matcher;

    protected $buf;

    protected $shortTags = [
        'area',
        'base',
        'br',
        'col',
        'command',
        'embed',
        'hr',
        'img',
        'input',
        'keygen',
        'link',
        'menuitem',
        'meta',
        'param',
        'source',
        'track',
        'wbr',
    ];

    public static function instance()
    {
        if (null === static::$instance) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function dir($dir)
    {
        if (!is_dir($dir)) {
            throw new LogicException(sprintf('The "%s" directory does not exist.', $dir));
        }
        $dirItr   = new RecursiveDirectoryIterator($dir);
        $itrItr   = new RecursiveIteratorIterator($dirItr);
        $regexItr = new RegexIterator($itrItr, '/^.+\.php$/i');
        foreach ($regexItr as $file) {
            require_once $file->getPathName();
        }
    }

    public function match($expr, $func)
    {
        $this->matches[] = [
            '__id' => sprintf('__func%d', $this->lastMatchId++),
            'expr' => $expr,
            'func' => $func,
        ];

        $this->matcher = null;

        return $this;
    }

    public function toHtml($arr)
    {
        $this->buf = '';
        $this->html($arr);
        $buf = $this->buf;
        unset($this->buf);

        return $buf;
    }

    public function process($arr)
    {
        $result  = [$arr];
        $nodes[] = new Entity([
            'index' => 0,
            'block' => null,
            'arr'   => $arr,
        ]);

        $compiledMatchers = $this->matcher ?: $this->matcher = $this->buildMatcher();

        $ctx = new Context;

        while ($node = array_shift($nodes)) {
            if (is_scalar($node->arr)) {
                continue;
            } elseif (is_array($node->arr)) {
                foreach ($node->arr as $index => $child) {
                    if (!is_object($child)) {
                        continue;
                    }
                    $nodes[] = new Entity([
                        'index'    => $index,
                        'position' => $index + 1,
                        'block'    => $node->block,
                        'arr'      => $child,
                        'parent'   => $node,
                    ]);
                }
                $result[$node->index] = $node->arr;
                continue;
            }

            if ($node->arr->elem) {
                $node->arr->block = $node->block;
            } elseif ($node->arr->block) {
                $node->block = $node->arr->block;
            }

            $ctx->node = $node;
            $ctx->arr  = $node->arr;

            $res = $compiledMatchers($ctx, $node->arr);
            if (null !== $res) {
                $node->arr = $res;
                $nodes[]   = $node;
                continue;
            }

            if (is_array($node->arr->content)) {
                foreach ($node->arr->content as $child) {
                    if (!is_object($child)) {
                        continue;
                    }
                    $nodes[] = new Entity([
                        'index'    => $index,
                        'position' => $index + 1,
                        'block'    => $node->block,
                        'arr'      => $child,
                        'parent'   => $node,
                    ]);
                }
            } elseif ($node->arr->content) {
                $nodes[] = new Entity([
                    'index'  => 'content',
                    'block'  => $node->block,
                    'arr'    => $node->arr->content,
                    'parent' => $node,
                ]);
            }
        }

        return $result[0];
    }

    public function render($arr)
    {
        return $this->toHtml($this->process($arr));
    }

    protected function buildMatcher()
    {
        $groupBy = function (array $declarations, $group) {
            $result = [];
            foreach ($declarations as $declaration) {
                $value = isset($declaration[$group]) ? $declaration[$group] : '__no_value__';
                isset($result[$value]) || $result[$value] = [];
                $result[$value][] = $declaration;
            }

            return $result;
        };

        $declarations = [];
        foreach ($this->matches as $index => $match) {
            $decl = [
                '__id'  => $match['__id'],
                'index' => $index,
                'func'  => $match['func'],
            ];
            if (false !== strpos($match['expr'], $this->optDelimElem)) {
                $exprBits      = explode($this->optDelimElem, $match['expr']);
                $blockExprBits = explode($this->optDelimMod, $exprBits[0]);
                $decl['block'] = $blockExprBits[0];
                if (sizeof($blockExprBits) > 2) {
                    $decl['blockMod']    = $blockExprBits[1];
                    $decl['blockModVal'] = $blockExprBits[2] || true;
                }
                $elemExprBits = explode($this->optDelimMod, $exprBits[1]);
                $decl['elem'] = $elemExprBits[0];
                if (sizeof($exprBits) > 2) {
                    $decl['elemMod']    = $elemExprBits[1];
                    $decl['elemModVal'] = $elemExprBits[2] || true;
                }
            } else {
                $exprBits      = explode($this->optDelimMod, $match['expr']);
                $decl['block'] = $exprBits[0];
                if (sizeof($exprBits) > 2) {
                    $decl['blockMod']    = $exprBits[1];
                    $decl['blockModVal'] = $exprBits[2] || true;
                }
            }
            $declarations[] = $decl;
        }

        $res[] = 'return function(array $matches) {';
        $res[] = 'return function($ctx, $arr) use ($matches) {';
        $res[] = 'switch ($arr->block) {';
        foreach ($groupBy($declarations, 'block') as $blockName => $blockDecls) {
            $res[] = sprintf('case "%s":', $blockName);
            $res[] = 'switch ($arr->elem) {';
            foreach ($groupBy($blockDecls, 'elem') as $elemName => $elemDecls) {
                $res[] = '__no_value__' === $elemName ? 'default:' : sprintf('case "%s":', $elemName);
                foreach ($elemDecls as $elemDecl) {
                    $conds = [sprintf('!$arr->%s', $elemDecl['__id'])];
                    $pairs = [
                        'blockMod' => 'blockModVal',
                        'elemMod'  => 'elemModVal',
                    ];
                    foreach ($pairs as $modifier => $value) {
                        if (!isset($elemDecl[$modifier])) {
                            continue;
                        }
                        $conds[] = sprintf(
                            '$arr->mods && %s === $arr->mods["%s"]',
                            $elemDecl[$modifier],
                            true === $elemDecl[$value] ? 'true' : sprintf('"%s"', $elemDecl[$value])
                        );
                    }
                }
                $res[] = sprintf('if (%s) {', implode(' && ', $conds));
                $res[] = sprintf('$arr->%s = true;', $elemDecl['__id']);
                $res[] = sprintf('return $matches[%d]["func"]($ctx, $arr);', $elemDecl['index']);
                $res[] = '}';
            }
            $res[] = '}';
            $res[] = 'break;';
        }
        $res[] = '}';
        $res[] = '};';
        $res[] = '};';

        $code = eval(join("\n", $res));

        return $code($this->matches);
    }

    protected function html($arr)
    {
        if (!$arr) {
            $this->buf .= '';

            return;
        }

        if (is_scalar($arr)) {
            $this->buf .= $this->optEscapeContent ?
                str_replace(['&', '<', '>'], ['&amp;', '&lt;', '&gt;'], $arr) :
                $arr;

            return;
        }

        if (is_array($arr)) {
            foreach ($arr as $value) {
                $value && $this->html($value);
            }

            return;
        }

        if ($arr instanceof Closure) {
            $this->html($arr());

            return;
        }

        $attrs = '';
        if ($arr->attrs) {
            foreach ($arr->attrs as $key => $value) {
                if (true === $value) {
                    $attrs .= sprintf(' %s', $key);
                } elseif (null !== $value && false !== $value) {
                    $value = str_replace(['&', '"'], ['&amp;', '&quot;'], $value);
                    $attrs .= sprintf(' %s="%s"', $key, $value);
                }
            }
        }

        $toBemCssClasses = function ($ctx, $base, $parentBase = null) {
            $res = '';
            if ($parentBase !== $base) {
                if ($parentBase) {
                    $res .= ' ';
                }
                $res .= $base;
            }
            if ($ctx->mods) {
                foreach ($ctx->mods as $mod) {
                    if ($mod || 0 === $mod) {
                        $res .= ' ' .
                                ($this->optNobaseMods ? $this->optDelimMod : $base . $this->optDelimMod) .
                                $mod .
                                (true === $mod ? '' : $this->optDelimMod . $mod);
                    }
                }
            }

            return $res;
        };

        $cls      = '';
        $jsParams = [];
        if (false !== $arr->bem) {
            $base = $arr->block . ($arr->elem ? $this->optDelimElem . $arr->elem : '');
            if ($arr->block) {
                $cls = $toBemCssClasses($arr, $base);
                if ($arr->js) {
                    $jsParams[$base] = true === $arr->js ? [] : $arr->js;
                }
            }

            $addJsInitClass = $this->optJsCls && ($this->optJsElem || !$arr->elem);
            $hasMixJsParams = false;

            if ($arr->mix) {
                foreach ($arr->mix as $mix) {
                    if (!$mix || false === $mix->bem) {
                        continue;
                    }
                    $mixBlock = $mix->block ?: ($arr->block ?: '');
                    $mixElem  = $mix->elem ?: ($mix->block ? null : ($arr->block ? $arr->elem : null));
                    $mixBase  = $mixBlock . ($mixElem ? $this->optDelimElem . $mixElem : '');
                    if (!$mixBlock) {
                        continue;
                    }
                    $cls .= $toBemCssClasses($mix, $mixBase, $base);
                    if (!$mix->js) {
                        continue;
                    }
                    $jsParams[$mixBase] = true === $mix->js ? [] : $mix->js;
                    $hasMixJsParams     = true;
                    $addJsInitClass || $addJsInitClass = $mixBlock && ($this->optJsCls && ($this->optJsElem || !$mixElem));
                }
            }

            if ($jsParams) {
                $addJsInitClass && $cls .= ' ' . $this->optJsCls;
                $jsData = !$hasMixJsParams && true === $arr->js ?
                    sprintf('{"%s":{}}', $base) :
                    json_encode($jsParams);
                $attrs .= sprintf(
                    " %s='%s'",
                    $this->optJsAttrName,
                    $this->optJsAttrIsJs ? 'return ' . $jsData : $jsData
                );
            }
        }

        $arr->cls && $cls = ($cls ? $cls . ' ' : '') . $arr->cls;

        $tag = $arr->tag ?: 'div';
        $this->buf .= '<' . $tag . ($cls ? ' class="' . $cls . '"' : '') . ($attrs ?: '');
        if (in_array($tag, $this->shortTags)) {
            $this->buf .= '>';
        } else {
            $this->buf .= '>';
            $this->html($arr->content);
            $this->buf .= sprintf('</%s>', $tag);
        }
    }
}
