<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI,
    Phalcon\Cli\Console as ConsoleApp;

define('VERSION', '1.0.0');

// Using the CLI factory default services container
$di = new CliDI();

// Load the configuration file (if any)
$config = include __DIR__ . '/config/config.php';
$di->set('config', $config);

/**
 * Register the autoloader and tell it to register the tasks directory
 */
include __DIR__ . '/config/loader.php';

// Create a console application
$console = new ConsoleApp();
$console->setDI($di);

/**
 * Process the console arguments
 */
$arguments = array();
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

// Define global constants for the current task and action
define('CURRENT_TASK',   (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
    switch ($arguments['task']) {
        case 'seed':
            $console->handle(['task' => 'App\Tasks\Seed']);
            break;
        case 'migrate':
            $console->handle(['task' => 'App\Tasks\Migrate']);
            break;
    }

} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
    exit(255);
}