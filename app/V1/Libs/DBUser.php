<?php

namespace App\V1\Libs;

use EasyAPI\Exceptions\HttpException;

class DBUser
{
    public function createUser(string $username, string $email, string $password): int
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $users = $this->getDataBase();

        foreach($users as $user) {

            if($username === $user['username'] && $email === $user['email']) {
                throw new HttpException("Username and email already exists", 422);
            }
            elseif($username === $user['username']) {
                throw new HttpException("Username already exists", 422);
            }
            elseif($email === $user['email']) {
                throw new HttpException("Email already exists", 422);
            }
        }

        return 4;
    }

    public function getUser(int $user_id): array
    {

        $users = $this->getDataBase();

        foreach($users as $user) {
            if($user['id'] === $user_id) {
                return $user;
            }
        }

        return [];
    }

    public function getUserByEmail(string $email): array
    {

        $users = $this->getDataBase();

        foreach($users as $user) {
            if($user['email'] === $email) {
                return $user;
            }
        }

        return [];
    }

    public function getAllUsers(): array
    {
        return $this->getDataBase();
    }

    private function getDataBase(): array
    {
        return json_decode(file_get_contents($_ENV['ROOT_PROJECT'] . '/app/V1/db/users.json'), true);
    }

}