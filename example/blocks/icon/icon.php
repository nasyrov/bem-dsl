<?php

$match->add('icon', function ($ctx, $arr) {
    $ctx->tag('span');

    if ($arr->url) {
        $ctx->attr('style', sprintf('background-image:url(%s)', $arr->url));
    }
});
