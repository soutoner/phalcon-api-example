<?php

/**
 * Needed by Codeception Phalcon2 module.
 */

// Enter TEST environment
putenv('APP_ENV=test');

$config = include __DIR__  .'/config.php';

require __DIR__ . '/loader.php';

$di = new \Phalcon\DI\FactoryDefault();

require  __DIR__ . '/services.php';

$app = new \Phalcon\Mvc\Micro($di);

/**
 * Mount routes collections
 */
$collections = include __DIR__ . '/../collections/collections.php';
foreach ($collections as $collection) {
    $app->mount($collection);
}

return $app;