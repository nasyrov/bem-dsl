<?php

use Lego\DSL\Context;
use Lego\DSL\ContextInterface;

$engine->registerMatcher('page', function (ContextInterface $context) {
    $context->tag('body');

    return [
        '<!doctype html>',
        (new Context)->tag('html')->classes(['ua_js_no'])->content(
            (new Context)->element('head')->content(
                (new Context)->element('meta')->attributes('charset', 'utf-8'),
                (new Context)->element('meta')->attributes([
                    'http-equiv' => 'x-ua-compatible',
                    'content'    => 'ie=edge',
                ])
            ),
            $context
        ),
    ];
});

$engine->registerMatcher('page__head', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('head');
});

$engine->registerMatcher('page__meta', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('meta');
});

$engine->registerMatcher('page__link', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('link')
            ->attributes('rel', 'stylesheet');
});

$engine->registerMatcher('page__favicon', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('link')
            ->attributes('rel', 'shortcut icon');
});
