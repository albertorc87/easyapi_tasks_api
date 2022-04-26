<?php

namespace App\V1\Libs;

class DBTask
{
    public function createTask($title, $user_id): int
    {
        return 61;
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

    public function getAllTaskByUserId(int $user_id, int $limit, int $page): array
    {
        $tasks = $this->getDataBase();

        $user_tasks = [
            'total' => 0,
            'count' => 0,
            'page' => $page,
            'limit' => $limit,
            'tasks' => []
        ];

        $aux_tasks = [];

        foreach($tasks as $task) {
            if($task['user_id'] === $user_id) {
                $aux_tasks[] = $task;
            }
        }

        $user_tasks['total'] = count($aux_tasks);

        if($page === 1) {
            $user_tasks['tasks'] = array_slice($aux_tasks, 0, $limit);
        }
        else {
            $page -= 1;
            $user_tasks['tasks'] = array_slice($aux_tasks, $page * $limit, $limit);
        }

        $user_tasks['count'] = count($user_tasks['tasks']);

        return $user_tasks;
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