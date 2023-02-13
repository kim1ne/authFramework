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
        $redis->set(Crypt::encode($cryptLogin), $this->id, self::SESSION_TIME);
    }

    public static function current(): bool|self
    {
        $request = ServerRequestFactory::fromGlobals();
        $cookieData = $request->getCookieParams()['auth'] ?? [];

        $redis = new \Redis();
        $redis->connect(self::REDIS_CONNECT);
        if (empty($cookieData)) {
            $redis->del('auth');
            return false;
        }

        $userId = $redis->get(Crypt::encode($cookieData));
        return self::find($userId);
    }

    public static function isAuth(): bool|int
    {
        $request = ServerRequestFactory::fromGlobals();
        $cookieData = $request->getCookieParams()['auth'] ?? [];

        if (empty($cookieData)) {
            return false;
        }

        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $sessionToken = Crypt::encode($cookieData);

        if (!$redis->get($sessionToken)) {
            setcookie('auth', '', time()-(60*60*24), '/');
        }

        return $redis->get($sessionToken);
    }

    public static function getTableName(): string
    {
        return 'users';
    }
}