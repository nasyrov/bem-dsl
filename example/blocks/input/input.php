<?php

use Lego\DSL\Context;
use Lego\DSL\Context\ContextInterface;
use function Lego\DSL\element as e;
use function Lego\DSL\matcher as m;

m('input', function (ContextInterface $context) {
    $attributes = $context->attributes();

    $context->js(true)
            ->tag('span')
            ->attributes([], true)
            ->content(
                e('box')->content(
                    e('control')->attributes($attributes)
                ),
                true
            );
});
