<?php

use Lego\DSL\ContextInterface;

$engine->matcher('input__box', function (ContextInterface $context) {
    $context->tag('span');
});
