<?php

use Lego\DSL\Context\ContextInterface;
use function Lego\DSL\matcher as m;

m('button__text', function (ContextInterface $context) {
    $context->tag('span');
});
