<?php

// Set TEST environment
putenv('APP_ENV=test');

// Load TEST configuration
$config = include __DIR__ . '/../../app/config/config.php';

// Create test database if not created
exec("mysql -u root -e 'CREATE DATABASE IF NOT EXISTS " . $config->database->dbname . ";'");

// Run migrations if necessary
exec('php vendor/bin/phalcon.php migration run --env=test');