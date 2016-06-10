<?php

use Lego\DSL\Context;
use Lego\DSL\Engine;

// include composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

// setup the engine
$engine = new Engine();

// include all the bricks declarations
$directoryIterator = new RecursiveDirectoryIterator('bricks');
foreach (new RecursiveIteratorIterator($directoryIterator) as $file) {
    if (!strpos($file, '.php')) {
        continue;
    }

    include $file;
}

// render
echo $engine->render(
    (new Context())->block('button')->cls(['test1'])->attrs('tabindex', 1)->content(
        (new Context())->elem('text')->content('Button text #1'),
        (new Context())->elem('text')->content('Button text #2')
    )
), "\n";
