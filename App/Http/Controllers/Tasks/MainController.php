<?php

namespace App\Http\Controllers\Tasks;

use App\Http\ControllerServices\Task\MainControllerServices;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use App\Services\Db\Db;
use App\Services\Db\Orm\Select;
use Bootstrap\Request;
use Bootstrap\Response;
use Zend\Diactoros\ServerRequestFactory;

class MainController
{
    public function __invoke(int $id)
    {
        $task = Task::find($id);
        if ($task->user_id != User::isAuth()) Response::error(403, 'Недостаточно прав');
        $task->updateStatus();
        $task->save();
    }

    public function index(int $categoryId, int $taskId)
    {
        $select = new Select([Category::getTableName() => 'category'], ['category_name']);
        $select->join([Task::getTableName() => 'task'], ['task.category_id' => $categoryId]);
        $select->where(['task.id' => $taskId, 'task.user_id' => User::isAuth()]);
        $sql = $select->build();

        $db = Db::getInstance();
        $db->sql($sql);
        $data = $db->fetchAll();

        Response::json(['status' => 'success', 'data' => $data]);
    }

    public function create()
    {
        $request = ServerRequestFactory::fromGlobals();
        $post = $request->getParsedBody();

        if (empty($post)) Response::error(400, 'Данные пустые');
        if (empty($post['task_name'])) Response::error(200, 'Введите имя задачи `task_name`');
        if (empty($post['category_id'])) Response::error(200, 'Введите `category_id`');

        $task = new Task();
        $task->set('task_name', $post['task_name']);
        $task->set('category_id', $post['category_id']);
        $task->set('user_id', User::isAuth());
        $task->set('status', 0);
        $task->save();
        Response::json(['status' => 'success', 'data' => $task]);
    }

    public function update()
    {
        $params = (new Request())->getParamsRequest();

        $task = MainControllerServices::update($params);
        $task->save();

        Response::json(['status' => 'success', 'data' => $task]);
    }

    public function delete()
    {
        $params = (new Request())->getParamsRequest();

        if (empty($params['id'])) Response::error(400, 'Выберите задачу');

        $task = Task::find($params['id']);

        if (empty($task)) Response::error(400, 'Задача не существует');

        if ($task->user_id != User::isAuth()) Response::error(400, 'Недостаточно прав');

        $task->delete();

        Response::json(['status' => 'success']);
    }
}