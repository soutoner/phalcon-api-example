<?php

namespace App\Tasks;

class MigrateTask extends \Phalcon\CLI\Task
{
    public function mainAction()
    {
        // Create test database if not created
        echo shell_exec("mysql -u root -e 'CREATE DATABASE IF NOT EXISTS " . $this->config->database->dbname . ";'");

        // Run migrations if necessary
        echo shell_exec('php vendor/bin/phinx migrate') . "\n";
    }
}