<?php

use BEM\DSL\Engine;
use BEM\DSL\Match\Collection;
use BEM\DSL\Match\Loader;
use function BEM\DSL\tag as t;
use function BEM\DSL\block as b;
use function BEM\DSL\element as e;

// include composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// setup DSL
function DSL()
{
    $matchLoader     = new Loader;
    $matchCollection = new Collection;

    $engine = new Engine($matchLoader, $matchCollection);
    $engine->setDirectory(__DIR__ . '/blocks');

    return $engine;
}

// render
echo DSL()->render(
    b('page', [
        'title'   => 'Social Services Search Robot',
        'favicon' => '/favicon.ico',
        'head'    => [
            e('meta', ['attrs' => ['name' => 'description', 'content' => 'find them all']]),
            e('css', ['url' => '_index.css']),
        ],
        'scripts' => [
            e('js', ['url' => '_index.js']),
        ],
        'content' => [
            b('header', [
                'content' => b('layout', [
                    'content' => [
                        e('left', [
                            'content' => b('form', [
                                'attrs'   => ['action' => 'http://yandex.ru/yandsearch'],
                                'content' => [
                                    b('input', [
                                        'mods'        => [
                                            'size'      => 'm',
                                            'has-clear' => true,
                                        ],
                                        'name'        => 'query',
                                        'val'         => 'Search ...',
                                        'placeholder' => 'try me, baby!'
                                    ]),
                                    b('button', [
                                        'mods' => [
                                            'size' => 'm',
                                            'type' => 'submit',
                                        ],
                                        'text' => 'Search'
                                    ])
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
