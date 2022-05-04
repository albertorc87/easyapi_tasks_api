<?php

namespace App\V1\Middlewares;

use EasyAPI\Middleware;
use EasyAPI\Request;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Exception;
use App\V1\Libs\DBUser;

use EasyAPI\Exceptions\HttpException;

class AdminAuth extends Middleware
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
        }
        catch(ExpiredException $e) {
            throw new HttpException('Your token has expired, please login again', 401);
        }
        catch(SignatureInvalidException $e) {
            throw new HttpException('Your token has expired, please login again', 401);
        }
        catch(Exception $e) {
            // TODO: Si pasa algo que no tenemos, aqui tenemos que avisarnos
            throw new HttpException('An error has ocurred when you make login, if persists, please contact');
        }

        $ddbb = new DBUser;
        $user = $ddbb->getUser($decoded->data->id);
        if(empty($user)) {
            throw new HttpException('User not exists', 404);
        }

        if($user['is_admin'] != 1) {
            throw new HttpException("You don't have the require permissions", 403);
        }

        return $request;
    }
}