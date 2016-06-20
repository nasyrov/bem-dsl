<?php

use Lego\DSL\Context\ContextInterface;
use function Lego\DSL\matcher as m;

m('input__control', function (ContextInterface $context) {
    $context->tag('input');
});
