<?php

namespace App\Tasks;

use App\Db\Seeds\DatabaseSeeder;

class SeedTask extends \Phalcon\CLI\Task
{
    public function mainAction()
    {
        // Create test database if not created
        exec("mysql -u root -e 'CREATE DATABASE IF NOT EXISTS " . $this->config->database->dbname . ";'");

        // Run migrations if necessary
        exec('php vendor/bin/phalcon.php migration run --env=dev');

        $success = DatabaseSeeder::Seed(true);

        if ($success) {
            echo "Database is successfully seeded \n";
        } else {
            echo "Ups, something went wrong while seeding \n";
        }
    }
}