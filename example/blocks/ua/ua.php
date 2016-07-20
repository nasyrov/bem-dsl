<?php


use function Lego\DSL\match as m;

m('ua', function ($context) {
    $context->bem(false)
            ->tag('script')
            ->content([
                '(function(e,c){',
                'e[c]=e[c].replace(/(ua_js_)no/g,"$1yes");',
                '})(document.documentElement,"className");'
            ], true);
});
