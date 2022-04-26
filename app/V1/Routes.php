<?php

namespace App\V1;

use EasyAPI\Router;

use App\V1\Controller\TaskController;
use App\V1\Controller\UserController;
use App\V1\Controller\AuthController;
use App\V1\Controller\UserTaskController;

use App\V1\Middlewares\BasicAuth;
use App\V1\Middlewares\AdminAuth;

class Routes {

    public static function loadRoutes()
    {

        Router::get('/v1/health', function() {
            return view('json', "Api it's alive");
        });

        // Tasks
        Router::get('/v1/tasks/(?<id>\d+)', TaskController::class . '@show', BasicAuth::class);
        Router::get('/v1/tasks', TaskController::class . '@all', BasicAuth::class);
        Router::post('/v1/tasks', TaskController::class . '@create', BasicAuth::class);
        Router::put('/v1/tasks/(?<id>\d+)', TaskController::class . '@update', BasicAuth::class);
        Router::delete('/v1/tasks/(?<id>\d+)', TaskController::class . '@delete', BasicAuth::class);

        // Users
        Router::post('/v1/users', UserController::class . '@create');
        Router::get('/v1/users', UserController::class . '@all', AdminAuth::class);

        // Login
        Router::post('/v1/login', AuthController::class . '@auth');

        // Admin
        Router::get('/v1/users/(?<id>\d+)/tasks', UserTaskController::class . '@getTasksByUser', AdminAuth::class);
        Router::get('/v1/users/(?<user_id>\d+)/tasks/(?<task_id>\d+)', UserTaskController::class . '@getTaskByUser', AdminAuth::class);

    }
}