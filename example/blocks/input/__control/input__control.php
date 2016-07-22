<?php

$match->add('input__control', function ($ctx, $arr) {
    $ctx->tag('input');

    $input = $ctx->param('_input');
    $attrs = [
        'id'          => $input->id,
        'name'        => $input->name,
        'value'       => $input->val,
        'maxlength'   => $input->maxLength,
        'tabindex'    => $input->tabIndex,
        'placeholder' => $input->placeholder,
    ];

    false === $input->autocomplete && $attrs['autocomplete'] = 'off';

    if (isset($input->mods['disabled'])) {
        $attrs['disabled'] = 'disabled';
    }

    $ctx->attrs($attrs);
});
