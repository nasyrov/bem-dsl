<?php

use Lego\DSL\ContextInterface;

$engine->matcher('button', function (ContextInterface $context) {
    $context->tag('button')
            ->attributes('role', 'button');
});
