<?php

namespace Bootstrap;

use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

class Response
{
    public static function error(int $code = 404, string $description = 'Страница не найдена'): void
    {
        if (!jsonSerialize()) {
            ob_start();
            view('error', ['error' => $code, 'description' => $description]);
            $page = ob_get_clean();
            $response =  (new HtmlResponse($page, $code));
        } else {
            $responseArray = ['error' => $description];
            $response = new JsonResponse($responseArray, $code);
        }

        self::emit($response);
        die;
    }

    public static function emit(\Zend\Diactoros\Response $response)
    {
        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }
}