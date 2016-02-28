<?php

namespace App\Db\Seeds;

use App\Db\Seeds\Models\UserSeeder;

class DatabaseSeeder
{
    /**
     * Call here Models seeders.
     *
     * TODO: (IMPROVEMENT) add transactions to the seeder.
     *
     * @param bool $want_fake : Whether to create fake users or not
     * @return bool : Successful seeding or not
     */
    public static function Seed($want_fake = true)
    {
        $success = UserSeeder::Seed($want_fake);

        return $success;
    }
}