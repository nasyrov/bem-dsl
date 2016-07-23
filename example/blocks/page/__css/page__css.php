<?php

$match->add('page__css', function ($ctx, $arr) {
    $ctx->bem(false);

    if ($arr->url) {
        $ctx->tag('link')
            ->attrs([
                'href' => $arr->url,
                'rel'  => 'stylesheet'
            ]);
    }
});
