<?php

namespace App\V1\Controller;

use App\V1\Libs\DBTask;
use EasyAPI\Exceptions\HttpException;

class UserTaskController
{
    public function getTasksByUser(int $id)
    {
        $ddbb = new DBTask();

        return view('json', $ddbb->getAllTaskByUserId($id));

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