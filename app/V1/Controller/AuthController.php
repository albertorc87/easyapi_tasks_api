<?php

namespace App\V1\Controller;

use Firebase\JWT\JWT;
use EasyAPI\Exceptions\HttpException;
use App\V1\Libs\DBUser;

class AuthController
{
    public function auth()
    {

        if(empty($_POST['email']) || empty($_POST['password'])) {
            throw new HttpException('You must send an email and password', 400);
        }

        $ddbb = new DBUser();

        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $ddbb->getUserByEmail($email);

        if(empty($user)) {
            throw new HttpException('User not found', 404);
        }

        if($user['is_active'] == 0) {
            throw new HttpException('Inactive user', 400);
        }

        if(!password_verify($password, $user['password'])) {
            throw new HttpException('Invalid password', 400);
        }

        $time = time();
        $payload = [
            'data' => [
                'id' => $user['id']
            ],
            'iat' => $time,
            'exp' => $time + (60 * 60 * 24 * 30)
        ];

        $jwt = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');

        return view('json', ['token' => $jwt]);
    }
}