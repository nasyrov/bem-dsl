<?php

use function BEM\DSL\elem as e;
use function BEM\DSL\match as m;

m('input', function ($ctx, $arr) {
    $ctx->tag('span')
        ->js(true)
        ->tParam('_input', $arr)
        ->content(
            e('box', ['content' => e('control')]),
            true
        );
});
