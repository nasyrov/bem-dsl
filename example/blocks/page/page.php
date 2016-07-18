<?php

use Lego\DSL\Context;
use Lego\DSL\Context\ContextInterface;
use function Lego\DSL\block as b;
use function Lego\DSL\element as e;
use function Lego\DSL\matcher as m;
use function Lego\DSL\tag as t;

m('page', function (ContextInterface $context) {
    $context->tag('body')
            ->content($context->content(), true);

    return [
        '<!DOCTYPE html>',
        t('html')->classes(['ua_js_no'])->content([
            e('head')->content([
                t('meta')->attributes('charset', 'utf-8'),
                e('meta')->attributes([
                    'http-equiv' => 'X-UA-Compatible',
                    'content'    => 'IE=edge',
                ]),
                b('ua')
            ]),
            $context
        ]),
    ];
});

m('page__head', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('head');
});

m('page__meta', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('meta');
});

m('page__link', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('link')
            ->attributes('rel', 'stylesheet');
});

m('page__favicon', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('link')
            ->attributes('rel', 'shortcut icon');
});
