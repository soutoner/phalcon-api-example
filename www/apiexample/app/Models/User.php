<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class User extends Model
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;
}
