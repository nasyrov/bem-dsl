<?php

use Lego\DSL\ContextInterface;

$engine->registerMatcher('button', function (ContextInterface $context) {
    $context->tag('button')
            ->attrs([
                'role' => 'button',
            ]);
});
