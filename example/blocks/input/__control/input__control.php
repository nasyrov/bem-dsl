<?php


use function Lego\DSL\match as m;

m('input__control', function ($ctx) {
    $ctx->tag('input');

    $input = $ctx->tParam('_input');
    $attrs = [
        'id'          => $input->id,
        'name'        => $input->name,
        'value'       => $input->val,
        'maxlength'   => $input->maxLength,
        'tabindex'    => $input->tabIndex,
        'placeholder' => $input->placeholder,
    ];

    false === $input->autocomplete && $attrs['autocomplete'] = 'off';

    if ($input->mods && $input->mods->disabled) {
        $attrs['disabled'] = 'disabled';
    }

    $ctx->attrs($attrs);
});
