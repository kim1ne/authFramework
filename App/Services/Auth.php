<?php


namespace App\Services;

use App\Exceptions\UserException;
use App\Models\User;

class Auth
{
    public static function auth(array $data): bool
    {
        if (User::isAuth()) {
            header('Location: /');
        }

        if (empty($data['login'])) {
            throw new UserException('Введите login');
        }
        if (empty($data['password'])) {
            throw new UserException('Введите Пароль');
        }

        $user = User::findByColumn('login', $data['login']);

        if (!$user) {
            throw new UserException('Пользователя не существует');
        }

        if (!password_verify($data['password'], $user->password)) {
            throw new UserException('Неверный пароль');
        }

        $user->authorize();

        return true;
    }
}