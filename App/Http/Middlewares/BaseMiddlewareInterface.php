<?php

namespace App\Http\Middlewares;

interface BaseMiddlewareInterface
{

    /*
     * Происходит проверка прав, доступа
     * Если вернётся false будет ошибка
     */
    public function verify(): bool;

    /*
     * Массив с http кодом ошибки http_response_code()
     * Вторым значением массива сообщение об ошибке
     */
    public function error(): array;
}