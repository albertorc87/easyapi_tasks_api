<?php

namespace App\V1\Middlewares;

use EasyAPI\Middleware;
use EasyAPI\Request;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Exception;

use EasyAPI\Exceptions\HttpException;

class BasicAuth extends Middleware
{
    public function handle(Request $request): Request
    {
        if(empty($_SERVER['HTTP_AUTHORIZATION'])) {
            throw new HttpException('You must send Authorization header', 422);
        }

        $token = $_SERVER['HTTP_AUTHORIZATION'];

        try {
            $decoded = JWT::decode($token, new Key($_ENV['JWT_KEY'], 'HS256'));
            $request->setData('user_id', $decoded->data->id);
            return $request;
        }
        catch(ExpiredException $e) {
            throw new HttpException('Your token has expired, please login again', 401);
        }
        catch(Exception $e) {
            // TODO: Si pasa algo que no tenemos, aqui tenemos que avisarnos
            throw new HttpException('An error has ocurred, please, make again login, if persists, contact with admin');
        }
    }
}