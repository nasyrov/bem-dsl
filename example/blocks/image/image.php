<?php


use function Lego\DSL\match as m;

m('image', function ($context) {
    if ($context->content()) {
        $context->tag('span')
                ->attr('role', 'img');
    } else {
        $context->tag('img');
    }
});
