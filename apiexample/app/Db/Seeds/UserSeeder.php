<?php

namespace App\Db\Seeds;

class UserSeeder extends BaseSeeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        if (getenv('APP_ENV') != 'test') {
            $faker = Faker\Factory::create();
            $data = [];
            for ($i = 0; $i < $this->n_fake_seeds; $i++) {
                // TODO: insert how a fake user is generated
            }

            $this->insert($this->table_name, $data);
        }
    }
}
