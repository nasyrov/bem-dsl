<?php

use Lego\DSL\Context;
use Lego\DSL\ContextInterface;

$engine->matcher('input', function (ContextInterface $context) {
    $context->js(true)
            ->tag('span')
            ->content(
                (new Context)->element('box')->content(
                    (new Context)->element('control')
                )
            );
});
