<?php

namespace App\Http\ControllerServices\User;

use App\Models\User;
use App\Services\Crypt;
use Bootstrap\Response;

class MainControllerServices
{
    public static function register(array $data): array
    {
        if (empty($data['login'])) {
            Response::error(201, "Введите логин");
        }

        if (empty($data['password'])) {
            Response::error(201, "Введите пароль");
        }

        $login = $data['login'];
        $unique = User::unique('login', $login);
        if (!$unique) {
            Response::error(201, "Логин `$login` занят");
        }
        foreach ($data as $paramName => $parameter) {
            if (empty($parameter)) {
                Response::error(201, "Заполните `$paramName`");
            }

            preg_match('/([а-яА-Я]+)/ui', $parameter, $match);
            if (!empty($match)) {
                Response::error(201, "Русские символы в `$paramName`");
            }
        }

        $data['password'] = password_hash ($data['password'], PASSWORD_DEFAULT);

        return $data;
    }

    public static function logout($cookieData): void
    {
        if (empty($cookieData)) {
            Response::error(401, "Вы не авторизованы");
        }

        $user = User::current();
        $redis = new \Redis();
        $redis->pconnect(User::REDIS_CONNECT);
        $redis->del(Crypt::encode($cookieData));
        setcookie('auth', $cookieData, time()-(60*60*24), '/');
    }
}