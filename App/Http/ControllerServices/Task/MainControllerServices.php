<?php

namespace App\Http\ControllerServices\Task;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Bootstrap\Response;

class MainControllerServices
{
    public static function update(array $params): Task
    {
        if (empty($params)) Response::error(400, 'Данные пустые');
        if (empty($params['id'])) Response::error(400, 'Выберите задачу');
        if (empty($params['task_name'])) Response::error(400, 'Введите название задачи `task_name`');
        if (empty($params['category_id'])) Response::error(400, 'Выберите категорию');

        $category = Category::find($params['category_id']);

        if (empty($category)) Response::error(404, 'Категория не найдена');
        if ($category->user_id != User::isAuth()) Response::error(400, 'Недостаточно прав');

        $task = Task::find($params['id']);

        if (empty($task)) Response::error(404, 'Задача не найдена');
        if ($task->user_id != User::isAuth()) Response::error(400, 'Недостаточно прав');

        foreach ($params as $property => $value) {
            if ($property === 'status') continue;
            $task->set($property, $value);
        }

        return $task;
    }
}