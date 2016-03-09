<?php

namespace Helper;

use App\Db\Seeds\DatabaseSeeder;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    /**
     * Before each test.
     *
     * @param \Codeception\TestCase $test
     */
    public function _before(\Codeception\TestCase $test)
    {
        // Populate DB
        $success = DatabaseSeeder::Seed(false);

        if (!$success) {
            echo "Ups something went wrong while seeding TEST database. \n";
        }
    }
}
