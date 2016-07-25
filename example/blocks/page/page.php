<?php

use function BEM\DSL\block as b;
use function BEM\DSL\element as e;
use function BEM\DSL\tag as t;

$match->add('page', function ($ctx, $arr) {
    $ctx->tag('body')
        ->param('nonce', $arr->nonce)
        ->content([
            $ctx->content(),
            $arr->scripts
        ], true);

    return [
        $arr->doctype ?: '<!DOCTYPE html>',
        t('html', [
            'cls'     => 'ua_js_no',
            'content' => [
                e('head', [
                    'content' => [
                        t('meta', ['attrs' => ['charset' => 'utf-8']]),
                        $arr->uaCompatible === false ? '' : t('meta', [
                            'attrs' => [
                                'http-equiv' => 'X-UA-Compatible',
                                'content'    => 'IE=edge',
                            ]
                        ]),
                        t('title', ['content' => $arr->title]),
                        b('ua'),
                        $arr->head,
                        $arr->styles,
                        $arr->favicon ? e('favicon', ['url' => $arr->favicon]) : '',
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

$match->add('page__favicon', function ($ctx, $arr) {
    $ctx->bem(false)
        ->tag('link')
        ->attr('rel', 'shortcut icon')
        ->attr('href', $arr->url);
});
