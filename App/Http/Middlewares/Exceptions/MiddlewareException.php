<?php

namespace App\Http\Middlewares\Exceptions;

use Bootstrap\Route;
use Throwable;

class MiddlewareException extends \Exception
{
    public function __construct($message = "", $code = 401, Throwable $previous = null)
    {
        http_response_code($code);
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return "{$this->code}: {$this->message}";
    }

    public function wrong()
    {
        $request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();

        if ($request->getHeaders()['accept'][0] === 'application/json') {

            $responseArray = ['error' => $this->getMessage()];

            $response = new \Zend\Diactoros\Response\JsonResponse($responseArray, $this->getCode());
            $emit = new \Zend\Diactoros\Response\SapiEmitter();
            $emit->emit($response);
            die;
        }
        \Bootstrap\Route::error($this->getCode(), $this->getMessage());
    }
}