<?php

namespace App\V1\Controller;

use App\V1\Libs\DBTask;
use EasyAPI\Exceptions\HttpException;
use App\V1\Libs\Auth;
use EasyAPI\Request;

class TaskController
{
    public function show(int $id, Request $request)
    {
        Auth::isAuth($request);

        $user_id = $request->getData('user_id');
        $ddbb = new DBTask();
        $task = $ddbb->getTaskByUserId($id, $user_id);

        if(empty($task)) {
            throw new HttpException('Task not found', 404);
        }


        return view('json', $task);
    }

    public function all(Request $request)
    {
        Auth::isAuth($request);

        $user_id = $request->getData('user_id');

        $ddbb = new DBTask();

        $tasks = $ddbb->getAllTaskByUserId($user_id);
        return view('json', $tasks);
    }

    public function create(Request $request)
    {
        Auth::isAuth($request);

        $user_id = $request->getData('user_id');
        $post = json_decode(file_get_contents('php://input'), true);

        if(empty($post['title'])) {
            throw new HttpException('You must send title of task in body raw in format json', 400);
        }

        if(strlen($post['title']) < 3 || strlen($post['title']) > 255) {
            throw new HttpException('Size of title must be between 3 and 255', 422);
        }

        $ddbb = new DBTask();

        $task_id = $ddbb->createTask($post['title'], $user_id);

        $task = [
            'id' => $task_id,
            'user_id' => $user_id,
            'title' => $post['title'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null,
            'is_done' => false
        ];

        return view('json', ['task' => $task, 'msg' => 'Task has been created successfully'], 201);
    }

    public function update(int $id, Request $request)
    {
        Auth::isAuth($request);

        $user_id = $request->getData('user_id');

        $post = json_decode(file_get_contents('php://input'), true);

        if(empty($post['title']) && empty($post['is_done'])) {
            throw new HttpException('You must send at least one field to update', 400);
        }

        $ddbb = new DBTask();

        $task = $ddbb->getTaskByUserId($id, $user_id);

        if(empty($task)) {
            throw new HttpException('Task not found', 404);
        }

        if(!empty($post['title']) && strlen($post['title']) < 3 || strlen($post['title']) > 255) {
            throw new HttpException('Size of title must be between 3 and 255', 422);
        }

        if(!empty($post['is_done']) && !is_bool($post['is_done'])) {
            throw new HttpException('is_done must be boolean', 422);
        }

        $ddbb->updateTask($post, $user_id, $id);

        $task = $ddbb->getTaskByUserId($id, $user_id);

        $task['updated_at'] = date('Y-m-d H:i:s');
        $task['title'] = $post['title'] ?? $task['title'];
        $task['is_done'] = $post['is_done'] ?? $task['is_done'];

        return view('json', ['task' => $task, 'msg' => 'Task has been updated successfully']);
    }

    public function delete(int $id, Request $request)
    {
        Auth::isAuth($request);

        $user_id = $request->getData('user_id');

        $ddbb = new DBTask();

        $task = $ddbb->getTaskByUserId($id, $user_id);

        if(empty($task)) {
            throw new HttpException('Task not found', 404);
        }

        return view('raw', '', 204);
    }
}