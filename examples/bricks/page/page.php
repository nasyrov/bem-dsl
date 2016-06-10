<?php

use Lego\DSL\ContextInterface;

$engine->registerMatcher('page', function (ContextInterface $context) {
    $context->tag('body');
});
