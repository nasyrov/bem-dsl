<?php

use Lego\DSL\ContextInterface;

$engine->matcher('image', function (ContextInterface $context) {
    $context->tag('img');

    if ($context->content()) {
        $context->tag('span')
                ->attributes('role', 'img');

    }
});
