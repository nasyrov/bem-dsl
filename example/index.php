<?php

use Lego\DSL\Engine;
use function Lego\DSL\tag as t;
use function Lego\DSL\block as b;
use function Lego\DSL\elem as e;
use function Lego\DSL\render as r;

// include composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// setup the engine
$engine = Engine::instance();
$engine->dir(__DIR__ . '/blocks');

// render
echo r(
    b('page', [
        'content' => [
            b('header', [
                'content' => b('layout', [
                    'content' => [
                        e('left', [
                            'content' => t('form', [
                                'attrs'   => ['action' => 'http://yandex.ru/yandsearch'],
                                'content' => [
                                    b('input', ['name' => 's', 'value' => 'Search ...']),
                                    b('button')
                                ]
                            ])
                        ]),
                        e('right')
                    ]
                ])
            ]),
        ]
    ])
), "\n";
