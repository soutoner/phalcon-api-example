<?php

namespace App\Db\Seeds\Models;

class UserSeeder extends BaseSeeder
{
    protected static $n_fake_seeds = 30;

    /**
     * Define specific seeds that are inserted in database here.
     *
     * @var array
     */
    protected static $db_seeds = [
        [
            'id'                => 1,
            'name'              => 'Romeo',
            'email'             => 'theking@staysking.com',
        ], [
            'id'                => 2,
            'name'              => 'Daddy',
            'email'             => 'dy@sigueme.com',
        ],
    ];

    /**
     * Define seeds that are not inserted in database here.
     *
     * @var array
     */
    protected static $extra_seeds = [
        [
            'name'              => 'Nicky',
            'email'             => 'elperdon@enrique.com',
        ],
    ];

    public static function GenerateFake($faker)
    {
        return [
            'name'              => $faker->firstName,
            'email'             => $faker->unique()->email,
        ];
    }
}
