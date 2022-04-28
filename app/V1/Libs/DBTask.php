<?php

namespace App\V1\Libs;

use EasyAPI\Exceptions\HttpException;

class DBTask
{
    public function createTask($title, $user_id): int
    {
        return 91;
    }

    public function getTaskByUserId(int $task_id, int $user_id): array
    {
        $tasks = $this->getDataBase();

        foreach($tasks as $task) {
            if($task['id'] === $task_id && $task['user_id'] === $user_id) {
                return $task;
            }
        }

        return [];

    }

    public function getAllTaskByUserId(int $user_id): array
    {

        $limit = (int)($_GET['limit'] ?? 10);
        $page = (int)($_GET['page'] ?? 1);
        $is_done = $_GET['is_done'] ?? null;

        if($is_done === '') {
            $is_done = null;
        }

        if(!is_numeric($limit) || $limit < 1) {
            throw new HttpException('Invalid query param "limit", must be a number greather or equal 1');
        }
        if(!is_numeric($page) || $page < 1) {
            throw new HttpException('Invalid query param "page", must be a number greather or equal 1');
        }

        if(!in_array($is_done, [null, 'true', 'false'])) {
            throw new HttpException('Invalid query param "is_done", valid values "true" or "false"');
        }

        $aux_is_done = "";
        if($is_done === 'true') {
            $aux_is_done = "&is_done=$is_done";
            $is_done = true;
        }
        elseif($is_done === 'false') {
            $aux_is_done = "&is_done=$is_done";
            $is_done = false;
        }

        $tasks = $this->getDataBase();

        $user_tasks = [
            'total' => 0,
            'count' => 0,
            'page' => $page,
            'limit' => $limit,
            'is_done' => $is_done,
            'pagination' => [
                'previous' => '',
                'next' => ''
            ],
            'tasks' => []
        ];

        $aux_tasks = [];

        foreach($tasks as $task) {
            if($task['user_id'] === $user_id) {
                if(!is_null($is_done) && $is_done === $task['is_done']) {
                    $aux_tasks[] = $task;
                }
                elseif(is_null($is_done)) {
                    $aux_tasks[] = $task;

                }
            }
        }

        $user_tasks['total'] = count($aux_tasks);

        if($page === 1) {
            $user_tasks['tasks'] = array_slice($aux_tasks, 0, $limit);
            if(count($aux_tasks) > $limit) {
                $user_tasks['pagination']['next'] = $this->getUrl() . "?page=2&limit=$limit$aux_is_done";
            }
        }
        else {
            $page -= 1;
            $user_tasks['tasks'] = array_slice($aux_tasks, $page * $limit, $limit);
            $user_tasks['pagination']['previous'] = $this->getUrl() . "?page=$page&limit=$limit$aux_is_done";

            if(count($aux_tasks) > (($page * $limit) + $limit)) {
                $aux_page = $page + 2;
                $user_tasks['pagination']['next'] = $this->getUrl() . "?page=$aux_page&limit=$limit$aux_is_done";
            }

        }

        $user_tasks['count'] = count($user_tasks['tasks']);

        return $user_tasks;
    }

    private function getUrl(): string
    {
        $http = 'http://';
        if (isset($_SERVER['HTTPS'])) {
            $http = 'https://';
        }

        $path = $_SERVER['REQUEST_URI'];
        foreach(['?', '#'] as $char) {
            if(strpos($path, $char) !== false) {
                $path = strstr($path, $char, true);
            }
        }

        return $http . $_SERVER['HTTP_HOST'] . $path;
    }

    public function updateTask(array $params, int $user_id, int $task_id): void
    {
        return;
    }

    private function getDataBase(): array
    {
        return json_decode(file_get_contents($_ENV['ROOT_PROJECT'] . '/app/V1/db/tasks.json'), true);
    }
}