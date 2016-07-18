<?php

use Lego\DSL\Context\ContextInterface;
use function Lego\DSL\matcher as m;

m('ua__svg', function (ContextInterface $context) {
    $context->content([
        $context->content(),
        '(function(d,n){',
        'd.documentElement.className+=',
        '" ua_svg_"+(d[n]&&d[n]("http://www.w3.org/2000/svg","svg").createSVGRect?"yes":"no");',
        '})(document,"createElementNS");'
    ], true);
});
