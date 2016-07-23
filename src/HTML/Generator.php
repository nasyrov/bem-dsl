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

    protected $buffer;

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

    public function generate($bemArr)
    {
        $this->buffer = '';
        $this->html($bemArr);
        $buffer = $this->buffer;
        unset($this->buffer);

        return $buffer;
    }

    protected function html($arr)
    {
        if (!$arr) {
            $this->buffer .= '';

            return;
        } elseif (is_scalar($arr)) {
            $this->buffer .= $this->optEscapeContent ?
                str_replace(['&', '<', '>'], ['&amp;', '&lt;', '&gt;'], (string)$arr) :
                (string)$arr;

            return;
        } elseif (is_array($arr)) {
            foreach ($arr as $value) {
                $value && $this->html($value);
            }

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

        $cls      = '';
        $jsParams = [];
        if (false !== $arr->bem) {
            $base = $arr->block . ($arr->elem ? $this->optDelimElem . $arr->elem : '');
            if ($arr->block) {
                $cls = $this->toBemCssClasses($arr, $base);
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

                    $cls .= $this->toBemCssClasses($mix, $mixBase, $base);
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

        if ($arr->cls) {
            $cls = ($cls ? $cls . ' ' : '') . $arr->cls;
        }

        $tag = $arr->tag ?: 'div';
        $this->buffer .= '<' . $tag . ($cls ? ' class="' . $cls . '"' : '') . ($attrs ?: '');
        if (in_array($tag, $this->shortTags)) {
            $this->buffer .= '/>';
        } else {
            $this->buffer .= '>';
            $this->html($arr->content);
            $this->buffer .= sprintf('</%s>', $tag);
        }
    }

    protected function toBemCssClasses($ctx, $base, $parentBase = null)
    {
        $res = '';

        if ($parentBase !== $base) {
            $res .= $parentBase ? ' ' : $base;
        }

        if (!$ctx->mods) {
            return $res;
        }

        foreach ($ctx->mods as $key => $value) {
            if (!$value) {
                continue;
            }

            $res .= ' ' .
                    ($this->optNobaseMods ? $this->optDelimMod : $base . $this->optDelimMod) .
                    $key .
                    (true === $value ? '' : $this->optDelimMod . $value);
        }

        return $res;
    }
}
