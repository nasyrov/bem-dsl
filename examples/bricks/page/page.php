<?php

use Lego\DSL\Context;
use Lego\DSL\ContextInterface;

$engine->matcher('page', function (ContextInterface $context) {
    $context->tag('body');

    return [
        '<!doctype html>',
        (new Context)->tag('html')->classes(['ua_js_no'])->content(
            (new Context)->element('head')->content(
                (new Context)->element('meta')->attributes('charset', 'utf-8'),
                (new Context)->element('meta')->attributes([
                    'content'    => 'ie=edge',
                    'http-equiv' => 'x-ua-compatible',
                ]),
                (new Context)->block('ua')
            ),
            $context
        ),
    ];
});

$engine->matcher('page__head', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('head');
});

$engine->matcher('page__meta', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('meta');
});

$engine->matcher('page__link', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('link')
            ->attributes('rel', 'stylesheet');
});

$engine->matcher('page__favicon', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('link')
            ->attributes('rel', 'shortcut icon');
});
