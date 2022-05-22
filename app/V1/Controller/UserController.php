<?php

namespace App\V1\Controller;

use App\V1\Libs\DBUser;
use EasyAPI\Exceptions\HttpException;

class UserController
{
    public function create()
    {
        $fields = ['username', 'email', 'password'];

        foreach($fields as $field) {
            if(!isset($_POST[$field])) {
                throw new HttpException("You must send field $field", 400);
            }
        }

        if(strlen($_POST['username']) < 3 || strlen($_POST['username']) > 25) {
            throw new HttpException("The 'username' field must be between 3 and 25 characters long", 422);
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new HttpException("Invalid 'email' format", 422);
        }

        if(strlen($_POST['password']) < 6 || strlen($_POST['password']) > 64) {
            throw new HttpException("The 'password' field must be between 6 and 64 characters long", 422);
        }

        $ddbb = new DBUser();

        $user_id = $ddbb->createUser($_POST['username'], $_POST['email'], $_POST['password']);

        $user = [
            'id' => $user_id,
            'username' => $_POST['username'],
            'email' => $_POST['email'],
        ];

        return view('json', $user, 201);

    }

    public function all()
    {
        $ddbb = new DBUser;
        $users = $ddbb->getAllUsers();
        foreach($users as &$user) {
            unset($user['password']);
        }
        unset($user);

        return view('json', $users);
    }
}