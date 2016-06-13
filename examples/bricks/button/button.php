<?php

use Lego\DSL\ContextInterface;

$engine->registerMatcher('button', function (ContextInterface $context) {
    $context->tag('button')
            ->attributes('role', 'button');
});
