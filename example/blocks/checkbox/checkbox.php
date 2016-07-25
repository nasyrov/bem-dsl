<?php

use function BEM\DSL\element as e;

$match->add('checkbox', function ($ctx, $arr) {
    $ctx->tag('label')
        ->js(true)
        ->content([
            e('box', [
                'content' => e('control', [
                    'elem'     => 'control',
                    'checked'  => $ctx->mod('checked'),
                    'disabled' => $ctx->mod('disabled'),
                    'name'     => $arr->name,
                    'val'      => $arr->val
                ])
            ]),
            $arr->text ? e('text', ['content' => $arr->text]) : '',
        ]);
});
