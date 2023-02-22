<?php


namespace App\Http\ControllerServices\User;


use App\Exceptions\UserException;
use App\Models\User;
use App\Services\Crypt;

class MainControllerServices
{
    public static function register(array $data): array
    {
        if (empty($data['login'])) {
            throw new UserException("Введите логин");
        }

        if (empty($data['password'])) {
            throw new UserException("Введите пароль");
        }

        $login = $data['login'];
        $unique = User::unique('login', $login);
        if ($unique) {
            throw new UserException("Логин `$login` занят");
        }
        foreach ($data as $paramName => $parameter) {
            if (empty($parameter)) {
                throw new UserException("Заполните `$paramName`");
            }

            preg_match('/([а-яА-Я]+)/ui', $parameter, $match);
            if (!empty($match)) {
                throw new UserException("Русские символы в `$paramName`");
            }
        }

        $data['password'] = password_hash ($data['password'], PASSWORD_DEFAULT);

        return $data;
    }

    public static function logout($cookieData): void
    {
        if (empty($cookieData)) {
            throw new UserException('Вы не авторизованы');
        }

        $user = User::current();
        $redis = new \Redis();
        $redis->pconnect(User::REDIS_CONNECT);
        $redis->del(Crypt::encode($cookieData));
        setcookie('auth', $cookieData, time()-(60*60*24), '/');
    }
}