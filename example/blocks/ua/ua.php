<?php

use Lego\DSL\Context\ContextInterface;
use function Lego\DSL\matcher as m;

m('ua', function (ContextInterface $context) {
    $context->bem(false)
            ->tag('script')
            ->content([
                '(function(e,c){',
                'e[c]=e[c].replace(/(ua_js_)no/g,"$1yes");',
                '})(document.documentElement,"className");'
            ]);
});
