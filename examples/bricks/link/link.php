<?php

use Lego\DSL\ContextInterface;

$engine->matcher('link', function (ContextInterface $context) {
    $context->tag('a')
            ->attributes('role', 'link');

    $url = $context->attributes('href');

    if ($context->modifiers('disabled')) {
        $context->js($url ? ['url' => $url] : true)
                ->attributes('aria-disabled', true);
    } else {
        $context->js(true);
    }
});
