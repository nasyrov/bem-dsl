<?php

use function Lego\DSL\elem as e;
use function Lego\DSL\match as m;

m('input', function ($ctx, $arr) {
    $ctx->tag('span')
        ->js(true)
        ->tParam('_input', $arr)
        ->content(
            e('box', ['content' => e('control')]),
            true
        );
});
