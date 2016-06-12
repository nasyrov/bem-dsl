<?php

use Lego\DSL\Context;
use Lego\DSL\ContextInterface;

$engine->registerMatcher('page', function (ContextInterface $context) {
    $context->tag('body')->content($context->content());

    return [
        '<!DOCTYPE html>',
        (new Context)->tag('html')->cls(['ua_js_no'])->content(
            (new Context)->elem('head')->content(
                (new Context)->elem('meta')->attrs('charset', 'utf-8'),
                (new Context)->elem('meta')->attrs([
                    'http-equiv' => 'X-UA-Compatible',
                    'content'    => 'IE=edge',
                ])
            )
        ),
        $context
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
            ->tag('link');
});

$engine->registerMatcher('page__favicon', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('link')
            ->attrs('rel', 'shortcut icon');
});
