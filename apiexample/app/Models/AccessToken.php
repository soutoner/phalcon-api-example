<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class AccessToken extends Model
{
    public $id;

    public $access_token;

    public function initialize()
    {
        $this->setSource("access_token");

        // $this->hasManyToMany("id", );
    }
}