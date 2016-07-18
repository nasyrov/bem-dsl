<?php

use Lego\DSL\Engine;
use function Lego\DSL\tag as t;
use function Lego\DSL\block as b;
use function Lego\DSL\element as e;
use function Lego\DSL\render as r;

// include composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// setup the engine
$engine = Engine::instance();
$engine->directory(__DIR__ . '/blocks');

// render
echo r(
    b('page')->content([
        b('header')->content(
            b('layout')->content([
                e('left')->content(
                    t('form')->attributes('action', 'http://yandex.ru/yandsearch')->content([
                        b('input')->attributes(['name' => 'text', 'value' => 'Search']),
                        b('button')->attributes('type', 'submit')->content('Search')
                    ])
                ),
                e('right')->content(
                    b('logo')->content(
                        b('link')->attributes('href', 'https://ru.bem.info')->content([
                            b('image')->attributes(
                                'src',
                                'http://varya.me/online-shop-dummy/desktop.blocks/b-logo/b-logo.png'
                            ),
                            b('slogan')->content('A new way of thinking')
                        ])
                    )
                )
            ])
        ),
        b('products')->content([
            e('item')->content([
                e('title')->content('Apple iPhone 4S 32Gb'),
                e('image')->attributes(
                    'src',
                    'http://mdata.yandex.net/i?path=b1004232748_img_id8368283111385023010.jpg'
                ),
                e('price')->content(
                    e('link')->content('259')
                )
            ]),
            e('item')->content([
                e('title')->content('Apple iPhone 4S 32Gb'),
                e('image')->attributes(
                    'src',
                    'http://mdata.yandex.net/i?path=b1004232748_img_id8368283111385023010.jpg'
                ),
                e('price')->content(
                    e('link')->content('259')
                )
            ]),
            e('item')->content([
                e('title')->content('Apple iPhone 4S 32Gb'),
                e('image')->attributes(
                    'src',
                    'http://mdata.yandex.net/i?path=b1004232748_img_id8368283111385023010.jpg'
                ),
                e('price')->content(
                    e('link')->content('259')
                )
            ]),
            e('item')->content([
                e('title')->content('Apple iPhone 4S 32Gb'),
                e('image')->attributes(
                    'src',
                    'http://mdata.yandex.net/i?path=b1004232748_img_id8368283111385023010.jpg'
                ),
                e('price')->content(
                    e('link')->content('259')
                )
            ]),
        ]),
        b('footer')->content([
            '&copy;',
            b('link')->attributes('href', 'http://bem.info')->content('BEM team'),
            '2016'
        ]),
        b('test')->content(function () {
            return 'test';
        })
    ])
), "\n";
