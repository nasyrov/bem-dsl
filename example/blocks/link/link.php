<?php

$match->add('link', function ($ctx, $arr) {
    $ctx->tag('a');

    $attrs = ['role' => 'link'];
    $url   = $arr->url;

    if ($ctx->mod('disabled')) {
        $ctx->js($url ? ['url' => $url] : true)
            ->attr('aria-disabled', true);
    } else {
        if ($url) {
            $attrs['href'] = $url;
            $tabIndex      = $arr->tabIndex;
        } else {
            $tabIndex = $arr->tabIndex ?: 0;
        }

        $ctx->js(true);
    }

    isset($tabIndex) && $attrs['tabindex'] = $tabIndex;
    $arr->title && $attrs['title'] = $arr->title;
    $arr->target && $attrs['target'] = $arr->title;

    $ctx->attrs($attrs);
});
