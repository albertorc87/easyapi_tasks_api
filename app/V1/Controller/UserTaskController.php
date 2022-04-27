<?php

namespace App\V1\Controller;

use App\V1\Libs\DBTask;
use EasyAPI\Exceptions\HttpException;

class UserTaskController
{
    public function getTasksByUser(int $id)
    {
        $ddbb = new DBTask();

        $limit = $_GET['limit'] ?? 10;
        $page = $_GET['page'] ?? 1;

        if(!is_numeric($limit) || $limit < 1) {
            throw new HttpException('Invalid query param "limit", must be a number greather or equal 1');
        }
        if(!is_numeric($page) || $page < 1) {
            throw new HttpException('Invalid query param "page", must be a number greather or equal 1');
        }

        return view('json', $ddbb->getAllTaskByUserId($id, $limit, $page));

    }
    public function getTaskByUser(int $user_id, int $task_id)
    {
        $ddbb = new DBTask();

        $task = $ddbb->getTaskByUserId($task_id, $user_id);

        if(empty($task)) {
            throw new HttpException('Task not found', 404);
        }

        return view('json', $task);

    }
}