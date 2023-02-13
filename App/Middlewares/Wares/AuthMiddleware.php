<?php


namespace App\Middlewares\Wares;


use App\Models\User;

class AuthMiddleware
{
    public function verify(): bool
    {
        return User::isAuth();
    }

    public function errorCode(): int
    {
        return 401;
    }
}