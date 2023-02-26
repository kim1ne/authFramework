<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use App\Services\Db\Db;
use App\Services\Db\Orm\Delete;
use App\Services\Db\Orm\Select;
use Bootstrap\Request;
use Bootstrap\Response;
use Bootstrap\Route;
use Zend\Diactoros\ServerRequestFactory;

class MainController
{
    public function __invoke(int $id)
    {
        $select = new Select([Task::getTableName() => 'task']);
        $select->join([Category::getTableName() => 'c'], ['c.id' => $id], ['category_name']);
        $select->where(['task.user_id' => User::isAuth()]);

        $db = Db::getInstance();
        $tasks = $db->sql($select->build())->fetchAll();
        Response::json(['status' => 'success', 'data' => $tasks]);
    }

    public function categories()
    {
        $select = new Select([Category::getTableName() => 'user']);
        $select->where(['user_id' => User::isAuth()]);
        $sql = $select->build();

        $db = Db::getInstance();
        $db->sql($sql);
        $categories = $db->fetchAll();
        Response::json(['status' => 'success', 'data' => $categories]);
    }

    public function create()
    {
        $request = ServerRequestFactory::fromGlobals();
        $post = $request->getParsedBody();

        if (empty($post)) Response::error(400, 'Данные пустые');

        if (empty($post['category_name'])) Response::error(400, 'Отстутсвует название категории `category_name`');

        $category = new Category();
        $category->set('category_name', $post['category_name']);
        $category->set('user_id', User::isAuth());
        $category->save();
        Response::json(['status' => 'success', 'data' => $category->toArray()]);
    }

    public function delete(int $id)
    {
        $category = Category::find($id);

        if (!$category) Response::error(400, 'Категории не существует');


        if ($category->user_id != User::isAuth()) {
            Response::error(401, 'Ошибка доступа');
        }

        $select = new Select([Task::getTableName() => 'task']);
        $select->where(['task.user_id' => User::isAuth(), 'category_id' => $id]);

        $db = Db::getInstance();
        $db->sql($select->build());
        $tasks = $db->fetchAll();
        $tasksId = array_column($tasks, 'id');

        if (!empty($tasksId)) {
            $delete = new Delete(Task::getTableName());
            $delete->setCondition(['id' => $tasksId]);
            $db->sql($delete->build());
            $db->execute();
        }

        $category->delete();
        Response::json(['status' => 'success']);
    }

    public function update(int $id)
    {
        $category = Category::find($id);

        if (empty($category)) {
            Response::error();
        }

        if ($category->user_id != User::isAuth()) {
            Response::error(401, 'Ошибка доступа');
        }

        $params = (new Request())->getParamsRequest();

        if (empty($params)) Response::error(400, 'Данные пустые');

        $categoryName = $params['category_name'];

        $category->set('category_name', $categoryName);
        $category->save();
        Response::json(['status' => 'success', 'data' => $category]);
    }
}