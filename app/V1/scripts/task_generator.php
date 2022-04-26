<?php

    $id_ini = 1;

    $task_for_users = 20;

    $users = [1, 2, 3];

    $tasks = [];

    foreach($users as $user) {
        foreach (range(1, $task_for_users) as $num_task) {
            $task = [
                'id' => $id_ini,
                'user_id' => $user,
                'title' => "$id_ini task",
                'created_at' => date('Y-m-d H:i:s'),
            ];

            if($num_task > 10) {
                $task['updated_at'] = null;
                $task['is_done'] = false;
            }
            else {
                $task['updated_at'] = date('Y-m-d H:i:s');
                $task['is_done'] = true;
            }

            $tasks[] = $task;
            $id_ini++;
        }
    }

    echo json_encode($tasks) . PHP_EOL;