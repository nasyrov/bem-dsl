<?php

$match->add('checkbox__text', function ($ctx) {
    $ctx->tag('span')
        ->attr('role', 'presentation');
});
