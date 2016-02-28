<?php

// Required for vlucas/phpdotenv
include __DIR__ . '/../../vendor/autoload.php';

defined('APP_PATH') || define('APP_PATH', realpath('.'));

getenv('APP_ENV') || putenv('APP_ENV=development');

// Load required environment variables from .env in development or testing. Never in production.
// To avoid file loading overhead.
$env = getenv('APP_ENV');
$dotenv = new Dotenv\Dotenv(__DIR__);
if($env != 'production') {
    $dotenv->load();
}
// Required ENV vars for this app
$dotenv->required([
    'APP_ENV',
    'DB_HOST',
    'DB_USER',
    'DB_PASS',
    'DB_NAME',
    'APP_DOMAIN',
]);

$main_config = new \Phalcon\Config(array(
    'database' => array(
        'adapter'   => 'Mysql',
        'host'      => getenv('DB_HOST'),
        'username'  => getenv('DB_USER'),
        'password'  => getenv('DB_PASS'),
        'dbname'    => getenv('DB_NAME'),
        'charset'   => 'utf8',
    ),
    'application' => array(
        'controllersDir'    => APP_PATH . '/app/Controllers/',
        'modelsDir'         => APP_PATH . '/app/Models/',
        'migrationsDir'     => APP_PATH . '/app/Db/Migrations/',
        'baseUri'           => '/apiexample/',
        'domain'            => getenv('APP_DOMAIN'),
    ),
    'namespaces' => array(
        'App'       => APP_PATH . '/app/',
        'Faker'     => APP_PATH . '/vendor/fzaninotto/faker/src/Faker/',
    ),
    'debug'  => false,
));

// Merge configs depending on the app environment
if ($env) {
    $env_config = include APP_PATH . '/app/config/environments/' . $env . '.php';
    return $main_config->merge($env_config);
} else {
    return $main_config;
}
