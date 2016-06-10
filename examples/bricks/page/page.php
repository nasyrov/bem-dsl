<?php

use Lego\DSL\Context;
use Lego\DSL\ContextInterface;

$engine->registerMatcher('page', function (ContextInterface $context) {
    $context->tag('body');

    return [
        (new Context)->tag('html')->cls(['ua_js_no'])->content(
            (new Context)->elem('head')->content(
                (new Context)->elem('meta')->attrs('charset', 'utf-8')
            )
        )
    ];
});
