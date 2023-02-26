<?php

namespace App\Http\Controllers\User;

use App\Models\Category;
use App\Models\User;
use App\Services\Db\Db;
use App\Services\Db\Orm\Select;
use Bootstrap\Response;
use Bootstrap\Route;
use Zend\Diactoros\ServerRequestFactory;

class MainController
{
    public function __invoke()
    {
        if (!User::isAuth()) Route::redirect(href('user.register'));

        $select = new Select([Category::getTableName() => 'user']);
        $select->where(['user_id' => User::isAuth()]);
        $sql = $select->build();

        $db = Db::getInstance();
        $db->sql($sql);
        $categories = $db->fetchAll();
        return view('categories', ['categories' => $categories]);
    }

    public function delete()
    {
        $user = User::current();

        if (!$user) {
            Response::error(401, 'Ошибка авторизации');
        }

        if ($user->unAuthorize()) {
            $user->delete();
        } else {
            Response::error(401, 'Ошибка авторизации');
        }

        Response::json([
            'status' => 'success',
            'data' => 'Пользователь удалён'
        ]);
    }
}