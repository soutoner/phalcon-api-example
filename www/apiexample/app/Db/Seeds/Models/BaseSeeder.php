<?php

namespace App\Db\Seeds\Models;

use Faker\Factory;

abstract class BaseSeeder
{
    /**
     * Number of Faker seeds that will be inserted.
     *
     * @var int
     */
    protected static $n_fake_seeds = 10;

    /**
     * Define specific seeds that are inserted in database here.
     *
     * @var array
     */
    protected static $db_seeds = [];

    /**
     * Define seeds that are not inserted in database here.
     *
     * @var array
     */
    protected static $extra_seeds = [];

    /**
     * Populates the database.
     *
     * @param bool $want_fake : Whether to create fake seeds or not.
     * @return bool : Successful seeding or not.
     */
    public static function Seed($want_fake = true)
    {
        $class = 'App\\'. str_replace('Seeder', '', implode('\\', array_slice(explode('\\', get_called_class()), 3)));
        $success = true;
        foreach(static::$db_seeds as $params){
            $seed = new $class();
            $success = $seed->create($params);
        }

        if($want_fake) {
            $faker = Factory::create();
            for ($i = 0; $i < static::$n_fake_seeds; $i++) {
                $seed = new $class();
                $success = $seed->create(static::GenerateFake($faker));
            }
        }

        return $success;
    }

    /**
     * Generate fake parameters.
     *
     * @param  $faker
     * @return
     */
    public abstract static function GenerateFake($faker);

    /**
     * Returns seeds params that are saves in database.
     *
     * @return array
     */
    public static function DbSeeds()
    {
        return static::$db_seeds;
    }

    /**
     * Returns seeds params that are not saved in the database.
     *
     * @return array
     */
    public static function ExtraSeeds()
    {
        return static::$extra_seeds;
    }
}
