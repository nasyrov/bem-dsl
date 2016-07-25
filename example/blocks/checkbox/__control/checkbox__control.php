<?php

$match->add('checkbox__control', function ($ctx, $arr) {
    $ctx->tag('input');

    $attrs = [
        'type'  => 'checkbox',
        'name'  => $arr->name,
        'value' => $arr->val,
    ];

    $arr->checked && $attrs['checked'] = 'checked';
    $arr->disabled && $attrs['disabled'] = 'disabled';

    $ctx->attrs($attrs);
});
