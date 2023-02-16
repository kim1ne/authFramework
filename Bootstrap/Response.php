<?php

namespace Bootstrap;

use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

class Response
{
    public static function error(int $code, string $description = null): void
    {
        if (jsonSerialize()) {
            $responseArray = ['error' => $description];
            $response = new JsonResponse($responseArray, $code);
        } else {
            ob_start();
            view('error', ['error' => $code, 'description' => $description]);
            $page = ob_get_clean();
            $response =  (new HtmlResponse($page, $code));
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