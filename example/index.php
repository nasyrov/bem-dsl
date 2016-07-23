<?php

use BEM\DSL\Context\Processor;
use BEM\DSL\Engine;
use BEM\DSL\HTML\Generator;
use BEM\DSL\Match\Collection;
use BEM\DSL\Match\Compiler;
use BEM\DSL\Match\Loader;
use function BEM\DSL\tag as t;
use function BEM\DSL\block as b;
use function BEM\DSL\element as e;

// include composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// setup DSL
function DSL()
{
    $collection = new Collection;
    $loader     = new Loader($collection);
    $compiler   = new Compiler($collection);

    $processor = new Processor($compiler);

    $generator = new Generator;

    $engine = new Engine($loader, $collection, $processor, $generator);
    $engine->setDirectory(__DIR__ . '/blocks');

    return $engine;
}

// render
echo DSL()->apply(
    b('page', [
        'title'   => 'Social Services Search Robot',
        'favicon' => '/favicon.ico',
        'head'    => [
            e('meta', [
                'attrs' => [
                    'name'    => 'description',
                    'content' => 'find them all'
                ]
            ]),
        ],
        'styles'  => [
            e('css', ['url' => 'index.css']),
        ],
        'scripts' => [
            e('js', ['url' => 'index.js']),
        ],
        'content' => [
            b('sssr', [
                'content' => [
                    e('header', [
                        'content' => [
                            e('logo', [
                                'content' => [
                                    b('icon', [
                                        'mods' => [
                                            'type' => 'sssr',
                                        ]
                                    ]),
                                    'Social Services Search Robot:'
                                ]
                            ]),
                            b('form', [
                                'content' => [
                                    e('search', [
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
                                            ]),
                                            b('spin', [
                                                'mods' => [
                                                    'size' => 's',
                                                ]
                                            ])
                                        ]
                                    ]),
                                    e('filter', [
                                        'content' => call_user_func(function () {
                                            foreach (['twitter', 'instagram'] as $service) {
                                                $res[] = b('checkbox', [
                                                    'mods' => [
                                                        'size'    => 'l',
                                                        'checked' => 'twitter' === $service,
                                                    ],
                                                    'name' => $service,
                                                    'text' => $service
                                                ]);
                                            }

                                            return $res;
                                        })
                                    ])
                                ]
                            ])
                        ]
                    ]),
                    e('content')
                ]
            ])
        ]
    ])
), "\n";
