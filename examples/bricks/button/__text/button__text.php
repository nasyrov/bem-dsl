<?php

use Lego\DSL\ContextInterface;

$engine->registerMatcher('button__text', function (ContextInterface $context) {
    $context->tag('span');
});
