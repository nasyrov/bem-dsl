<?php

use Lego\DSL\Context;
use Lego\DSL\Engine;

// include composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// setup the engine
$engine = new Engine;
$engine->addMatcherDirectory(__DIR__ . '/bricks');

// render
echo $engine->render(
    (new Context)->block('page')->content(
        (new Context)->block('button')->modifiers('theme', 'green')->content(
            (new Context)->element('text')->content('Button text #1')
        ),
        (new Context)->block('button')->modifiers('theme', 'green')->content(
            (new Context)->element('text')->content('Button text #2')
        )
    )
), "\n";
