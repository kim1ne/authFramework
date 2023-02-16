<?php

namespace App\Http\Middlewares\Exceptions;

use App\Exceptions\WrongExceptionInterface;
use Bootstrap\Response;
use Throwable;

class MiddlewareException extends \Exception implements WrongExceptionInterface
{
    public function __construct($message = "", $code = 401, Throwable $previous = null)
    {
        http_response_code($code);
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return "{$this->code}: {$this->message}";
    }

    public function wrong(): void
    {
        Response::error($this->getCode(), $this->getMessage());
    }
}