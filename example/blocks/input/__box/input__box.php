<?php

use Lego\DSL\Context\ContextInterface;
use function Lego\DSL\matcher as m;

m('input__box', function (ContextInterface $context) {
    $context->tag('span');
});
