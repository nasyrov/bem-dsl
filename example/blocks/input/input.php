<?php

use function BEM\DSL\element as e;

$match->add('input', function ($ctx, $arr) {
    $ctx->tag('span')
        ->js(true)
        ->param('_input', $arr)
        ->content(
            e('box', ['content' => e('control')]),
            true
        );
});
