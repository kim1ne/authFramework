<?php


namespace App\Http\Middlewares\Wares;


use App\Http\Middlewares\BaseMiddlewareInterface;
use App\Models\User;

class AuthMiddleware implements BaseMiddlewareInterface
{
    public function verify(): bool
    {
        return is_int(User::isAuth());
    }

    public function error(): array
    {
        return [
            401,
            'Ошибка авторизации'
        ];
    }
}