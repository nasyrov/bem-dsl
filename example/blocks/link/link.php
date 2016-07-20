<?php


use function Lego\DSL\match as m;

m('link', function ($context) {
    $context->tag('a')
            ->attr('role', 'link');

    $url = $context->attr('href');

    if ($context->mod('disabled')) {
        $context->js($url ? ['url' => $url] : true)
                ->attr('aria-disabled', true);
    } else {
        $context->js(true);
    }
});
