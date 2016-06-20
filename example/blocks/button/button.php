<?php

use Lego\DSL\Context\ContextInterface;
use function Lego\DSL\matcher as m;

m('button', function (ContextInterface $context) {
    $context->tag('button');
});
