<?php

namespace App\Db\Seeds;

use Phinx\Seed\AbstractSeed;
use App\Modules\Strings\Helper;

class BaseSeeder extends AbstractSeed
{
    /**
     * Table name of the seeder.
     *
     * @var string
     */
    public $table_name;

    /**
     * Seeds that are always inserted into the database (available in tests)
     *
     * @var array
     */
    public $db_seeds;

    /**
     * Seeds that are not inserted into database (to be used in tests)
     *
     * @var array
     */
    public $extra_seeds;

    /**
     * Number of Faker seeds that are inserted into database (not in test).
     *
     * @var int
     */
    public $n_fake_seeds = 10;

    public function __construct()
    {
        // Set table name from child class name
        $seeder_name = Helper::NamespaceToClassName(get_called_class());
        $this->table_name = Helper::TableNameFromClassName($seeder_name, 'Seeder');
        // Always execute db_seeds insertion
        self::run();
    }

    /**
     * Method that populates the DB. Faker seeds are only made in `test` environment.
     */
    public function run(){
        if (!empty($this->db_seeds)) {
            // Insert db_seeds (always present)
            $table = $this->table($this->table_name);
            $table->insert($this->db_seeds)
                ->save();
        }
    }
}