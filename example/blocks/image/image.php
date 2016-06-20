<?php

use Lego\DSL\Context\ContextInterface;
use function Lego\DSL\matcher as m;

m('image', function (ContextInterface $context) {
    if ($context->content()) {
        $context->tag('span')
                ->attributes('role', 'img');
    } else {
        $context->tag('img');
    }
});
