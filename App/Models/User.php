<?php

namespace App\Models;

use App\Services\Crypt;
use Zend\Diactoros\ServerRequestFactory;

class User extends ActiveRecord
{
    const SESSION_TIME = 60 * 60 * 24;
    const SECRET_KEY = 'B3EhZ1oQArNRqJJJlWhu';
    const REDIS_CONNECT = '127.0.0.1';

    protected string $login;
    protected string $password;

    public static function userHashGetToken(string $token)
    {
        return $token . self::SECRET_KEY;
    }

    public function authorize()
    {
        $cryptLogin = Crypt::encode($this->login);

        $encode = substr($cryptLogin, 0, 20);

        $currentDate = time();
        $token = trim($currentDate) . '.' . trim($this->login) . '.' . $encode;


        setcookie('auth', $token, time() + self::SESSION_TIME, '/');

        $redis = new \Redis();
        $redis->connect(self::REDIS_CONNECT);
        $redis->set(self::userHashGetToken($token), $this->id, self::SESSION_TIME);

        return $token;
    }

    public function unAuthorize()
    {
        $userData = self::getTokenSession();

        if (empty($userData)) {
            return false;
        }

        $redis = new \Redis();
        $redis->connect(self::REDIS_CONNECT);

        $token = self::userHashGetToken($userData);

        if (empty($redis->get($token))) {
            return false;
        }

        $redis->del($token);

        return true;
    }

    private static function getTokenSession()
    {
        $request = ServerRequestFactory::fromGlobals();

        $userData = $request->getCookieParams()['auth'] ?? [];

        if (!empty($request->getHeaders()['authorize'][0])) {
            $userData = $request->getHeaders()['authorize'][0];
        }
        return $userData;
    }

    public static function current(): bool|self
    {
        $userData = self::getTokenSession();

        if (empty($userData)) {
            return false;
        }

        $redis = new \Redis();
        $redis->connect(self::REDIS_CONNECT);

        $token = self::userHashGetToken($userData);

        $userId = $redis->get($token);
        return self::find($userId) ?? false;
    }

    public static function isAuth(): bool|int
    {
        $userData = self::getTokenSession();
        if (empty($userData)) {
            return false;
        }

        $redis = new \Redis();
        $redis->connect('127.0.0.1');

        $token = self::userHashGetToken($userData);

        if (!$redis->get($token)) {
            setcookie('auth', '', time()-(self::SESSION_TIME), '/');
        }
        return $redis->get($token);
    }

    public static function getTableName(): string
    {
        return 'users';
    }
}