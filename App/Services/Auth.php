<?php


namespace App\Services;

use App\Models\User;
use Bootstrap\Response;

class Auth
{
    public static function auth(array $data): User
    {
        if (User::isAuth()) {
            header('Location: /');
        }

        if (empty($data['login'])) {
            Response::json(['status' => 'error', 'message' => 'Введите login']);
        }
        if (empty($data['password'])) {
            Response::json(['status' => 'error', 'message' => 'Введите Пароль']);
        }

        $user = User::findByColumn('login', $data['login']);

        if (!$user) {
            Response::json(['status' => 'error', 'message' => 'Пользователя не существует']);
        }

        if (!password_verify($data['password'], $user->password)) {
            Response::json(['status' => 'error', 'message' => 'Неверный пароль']);
        }

        return $user;
    }
}