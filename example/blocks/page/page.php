<?php

use function BEM\DSL\block as b;
use function BEM\DSL\element as e;
use function BEM\DSL\tag as t;

$match->add('page', function ($ctx, $arr) {
    $ctx->tag('body');

    return [
        $arr->doctype ?: '<!DOCTYPE html>',
        t('html', [
            'cls'     => 'ua_js_no',
            'content' => [
                e('head', [
                    'content' => [
                        e('meta', ['attrs' => ['charset' => 'utf-8']]),
                        e('meta', [
                            'attrs' => [
                                'http-equiv' => 'X-UA-Compatible',
                                'content'    => 'IE=edge',
                            ]
                        ]),
                        b('ua')
                    ]
                ]),
                $arr
            ]
        ]),
    ];
});

$match->add('page__head', function ($ctx) {
    $ctx->bem(false)
        ->tag('head');
});

$match->add('page__meta', function ($ctx) {
    $ctx->bem(false)
        ->tag('meta');
});
