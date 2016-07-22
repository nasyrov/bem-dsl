<?php


use function BEM\DSL\match as m;

m('input__box', function ($ctx) {
    $ctx->tag('span');
});
