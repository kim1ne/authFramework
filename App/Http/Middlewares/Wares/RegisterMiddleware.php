<?php


namespace App\Http\Middlewares\Wares;


use App\Models\User;

class RegisterMiddleware
{
    public function verify(): bool
    {
        return false;
    }

    public function error(): array
    {
        return [
            401,
            'Пользователь не зарегистрирован'
        ];
    }
}