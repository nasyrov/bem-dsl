<?php

use Lego\DSL\ContextInterface;

$engine->matcher('button__text', function (ContextInterface $context) {
    $context->tag('span');
});
