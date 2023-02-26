<?php

namespace Bootstrap;

use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

class Response
{
    public static function error(int $code = 404, string $description = 'Страница не найдена')
    {
        $accept = ServerRequestFactory::fromGlobals()->getHeaders()['accept'][0];
        preg_match('/application\/(\w+)/', $accept, $matches);
        if (empty($matches)) {
            $matches[1] = 'html';
        }
        unset($matches[0]);
        $keyFirst = array_key_first($matches);
        $matches = $matches[$keyFirst];
        switch ($matches) {
            case 'json':
                $reply = [
                    'status' => 'error',
                    'message' => $description
                ];
                $response = (new JsonResponse($reply, $code));
                $response->withHeader('Content-Type', 'application/json');
                break;
            default:
                $response = new HtmlResponse(view('error', ['error' => $code, 'description' => $description]));
                $response->withHeader('Content-Type', 'text/html; charset=utf-8');
                break;

        }

        self::emit($response);
    }

    public static function emit(\Zend\Diactoros\Response $response)
    {
        $emitter = new SapiEmitter();
        $emitter->emit($response);
        die;
    }

    public static function json(array $data)
    {
        $response = (new JsonResponse($data))
            ->withHeader('Content-Type', 'application/json');
        Response::emit($response);
    }
}