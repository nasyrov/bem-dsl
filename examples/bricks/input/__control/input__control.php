<?php

use Lego\DSL\ContextInterface;

$engine->matcher('input__control', function (ContextInterface $context) {
    $context->tag('input');
});
