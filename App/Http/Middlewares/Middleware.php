<?php

namespace App\Http\Middlewares;

use App\Http\Middlewares\Exceptions\MiddlewareException;
use Bootstrap\Response;
use Bootstrap\Route;

class Middleware
{
    const FOLDER_MIDDLEWARES = 'Wares';
    const PREFIX = 'Middleware';

    private string $middleware;

    /**
     * Middleware constructor.
     * @param string $middleware
     */
    public function __construct(string $middleware)
    {
        $this->middleware = $middleware;
        $this->run();
    }

    public function run()
    {
        $middleware = __NAMESPACE__ . '\\' . self::FOLDER_MIDDLEWARES . '\\' . ucfirst(strtolower($this->middleware)) . self::PREFIX;
        if (!class_exists($middleware)) Response::error(500, "Класс " . $middleware . " не найден");
        $middleware = new $middleware();
        if (!$middleware->verify()) {
            throw new MiddlewareException($middleware->error()[1], $middleware->error()[0]);
        }
    }
}