<?php namespace BEM\DSL\HTML;

class Generator implements GeneratorInterface
{
    protected $optJsAttrName = 'onclick';
    protected $optJsAttrIsJs = true;
    protected $optJsCls = 'i-bem';
    protected $optJsElem = true;
    protected $optEscapeContent = false;
    protected $optNobaseMods = false;
    protected $optDelimElem = '__';
    protected $optDelimMod = '_';

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

    public function generate($arr)
    {
        $this->buf = '';
        $this->html($arr);
        $buf = $this->buf;
        unset($this->buf);

        return $buf;
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
                foreach ($ctx->mods as $key => $value) {
                    if (!$value && 0 !== $value) {
                        continue;
                    }
                    $res .= ' ' .
                            ($this->optNobaseMods ? $this->optDelimMod : $base . $this->optDelimMod) .
                            $key .
                            (true === $value ? '' : $this->optDelimMod . $value);
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
                    $addJsInitClass || $addJsInitClass = $mixBlock &&
                                                         ($this->optJsCls && ($this->optJsElem || !$mixElem));
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
            $this->buf .= '/>';
        } else {
            $this->buf .= '>';
            $this->html($arr->content);
            $this->buf .= sprintf('</%s>', $tag);
        }
    }
}
