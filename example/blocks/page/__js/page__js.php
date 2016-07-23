<?php

$match->add('page__js', function ($ctx, $arr) {
    $ctx->bem(false)
        ->tag('script');

    if ($arr->url) {
        $ctx->attr('src', $arr->url);
    }
});
