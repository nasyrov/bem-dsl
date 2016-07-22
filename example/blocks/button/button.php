<?php

use function BEM\DSL\element as e;

$match->add('button', function ($ctx, $arr) {
    $ctx->tag($arr->tag ?: 'button');

    $modType      = $ctx->mod('type');
    $isRealButton = ('button' === $ctx->tag()) && (!$modType || 'submit' === $modType);

    $ctx->js(true)
        ->attrs([
            'role'     => 'button',
            'tabindex' => $arr->tabIndex,
            'id'       => $arr->id,
            'type'     => $isRealButton ? ($modType ?: 'button') : null,
            'name'     => $arr->name,
            'value'    => $arr->val,
            'title'    => $arr->title,
        ]);

    if ($ctx->mod('disabled')) {
        $isRealButton ?
            $ctx->attr('disabled', 'disabled') :
            $ctx->attr('aria-disabled', 'true');
    }

    if (null === $ctx->content() && $arr->text) {
        $ctx->content(e('text', ['content' => $arr->text]));
    }
});
