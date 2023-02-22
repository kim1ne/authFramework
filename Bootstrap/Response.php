<?php

namespace Bootstrap;

use Zend\Diactoros\Response\SapiEmitter;

class Response
{
    public static function error(int $code = 404, string $description = 'Страница не найдена')
    {
        $response = view('error', ['status' => 'error', 'message' => $description]);

        self::emit($response);
        die;
    }

    public static function emit(\Zend\Diactoros\Response $response)
    {
        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }
}