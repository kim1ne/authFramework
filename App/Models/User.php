<?php

namespace App\Models;

use App\Services\Crypt;
use Zend\Diactoros\ServerRequestFactory;

class User extends ActiveRecord
{
    const SESSION_TIME = 60 * 60 * 24;
    const REDIS_CONNECT = '127.0.0.1';

    protected string $login;
    protected string $password;

    public function authorize()
    {
        $cryptLogin = Crypt::encode($this->login);
        setcookie('auth', $cryptLogin, time() + self::SESSION_TIME, '/');
        $redis = new \Redis();
        $redis->connect(self::REDIS_CONNECT);
        $token = Crypt::encode($cryptLogin);
        $redis->set($token, $this->id, self::SESSION_TIME);
        return $token;
    }

    public static function current(): bool|self
    {
        $request = ServerRequestFactory::fromGlobals();
        $userData = $request->getCookieParams()['auth'] ?? [];

        if (!empty($request->getHeaders()['authorization'])) {
            $userData = $request->getHeaders()['authorization'][0];
        }

        if (empty($userData)) {
            return false;
        }

        $redis = new \Redis();
        $redis->connect(self::REDIS_CONNECT);
        $userId = $redis->get(Crypt::encode($userData));
        return self::find($userId) ?? false;
    }

    public static function isAuth(): bool|int
    {
        $request = ServerRequestFactory::fromGlobals();
        $userData = $request->getCookieParams()['auth'] ?? [];

        if (empty($userData) && !empty($request->getHeaders()['authorization'])) {
            $userData = $request->getHeaders()['authorization'][0];
        }


        if (empty($userData)) {
            return false;
        }

        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $sessionToken = Crypt::encode($userData);

        if (!$redis->get($sessionToken)) {
            setcookie('auth', '', time()-(self::SESSION_TIME), '/');
        }

        return $redis->get($sessionToken);
    }

    public static function getTableName(): string
    {
        return 'users';
    }
}