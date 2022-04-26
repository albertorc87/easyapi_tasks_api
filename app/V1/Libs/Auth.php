<?php

namespace App\V1\Libs;

use EasyAPI\Exceptions\HttpException;
use EasyAPI\Request;

class Auth
{
    public static function isAuth(Request $request): void
    {
        if(!$request->getData('user_id')) {
            throw new HttpException('You must be login', 401);
        }
    }
}