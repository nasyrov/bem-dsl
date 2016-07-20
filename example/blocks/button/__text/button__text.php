<?php

use function Lego\DSL\match as m;

m('button__text', function ($context) {
    $context->tag('span');
});
