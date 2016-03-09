<?php

namespace App\Controllers\V1;

use Phalcon\Http\Response;
use App\Models\User;

class UsersController extends BaseController
{
    // Enter to /api/v1/users to see this message
    public function index()
    {
        return new Response(json_encode(User::find()->toArray()));
    }
}
