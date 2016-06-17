<?php

use Lego\DSL\Context;
use Lego\DSL\Engine;

// include composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// setup the engine
$engine = new Engine;
$engine->directory(__DIR__ . '/bricks');

// render
echo $engine->render(
    (new Context)->block('page')->content(
        (new Context)->block('head')->content(
            (new Context)->block('layout')->content(
                (new Context)->element('left')->content(
                    (new Context)->tag('form')->attributes('action', 'http://yandex.ru/yandsearch')->content(
                        (new Context)->block('input')->attributes(['name' => 'text', 'value' => 'Find']),
                        (new Context)->block('button')->attributes('type', 'submit')->content('Search')
                    )
                ),
                (new Context)->element('right')->content(
                    (new Context)->block('logo')->content(
                        (new Context)->block('link')->attributes('href', 'https://ru.bem.info')->content(
                            (new Context)->block('image')->attributes(
                                'src',
                                'http://varya.me/online-shop-dummy/desktop.blocks/b-logo/b-logo.png'
                            ),
                            (new Context)->block('slogan')->content('A new way of thinking')
                        )
                    )
                )
            )
        ),
        (new Context)->block('products')->content(
            (new Context)->element('item')->content(
                (new Context)->element('title')->content('Apple iPhone 4S 32Gb'),
                (new Context)->element('image')->attributes(
                    'src',
                    'http://mdata.yandex.net/i?path=b1004232748_img_id8368283111385023010.jpg'
                ),
                (new Context)->element('price')->content(
                    (new Context)->element('link')->content('259')
                )
            ),
            (new Context)->element('item')->content(
                (new Context)->element('title')->content('Apple iPhone 4S 32Gb'),
                (new Context)->element('image')->attributes(
                    'src',
                    'http://mdata.yandex.net/i?path=b1004232748_img_id8368283111385023010.jpg'
                ),
                (new Context)->element('price')->content(
                    (new Context)->element('link')->content('259')
                )
            ),
            (new Context)->element('item')->content(
                (new Context)->element('title')->content('Apple iPhone 4S 32Gb'),
                (new Context)->element('image')->attributes(
                    'src',
                    'http://mdata.yandex.net/i?path=b1004232748_img_id8368283111385023010.jpg'
                ),
                (new Context)->element('price')->content(
                    (new Context)->element('link')->content('259')
                )
            ),
            (new Context)->element('item')->content(
                (new Context)->element('title')->content('Apple iPhone 4S 32Gb'),
                (new Context)->element('image')->attributes(
                    'src',
                    'http://mdata.yandex.net/i?path=b1004232748_img_id8368283111385023010.jpg'
                ),
                (new Context)->element('price')->content(
                    (new Context)->element('link')->content('259')
                )
            )
        ),
        (new Context)->block('footer')->content(
            '&copy;',
            (new Context)->block('link')->attributes('href', 'http://bem.info')->content('BEM team'),
            '2016'
        )
    )
), "\n";
