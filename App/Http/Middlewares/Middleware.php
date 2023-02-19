<?php

namespace App\Http\Middlewares;

use App\Http\Middlewares\Exceptions\MiddlewareException;
use Bootstrap\Response;

class Middleware
{
    const FOLDER_MIDDLEWARES = 'Wares';
    const PREFIX = 'Middleware';

    private string $middlewareName;
    private BaseMiddlewareInterface $middleware;

    /**
     * Middleware constructor.
     * @param string $middleware
     */
    public function __construct(string $middleware)
    {
        $this->middlewareName = $middleware;
        $this->prepare();
        $this->control();
    }

    public function prepare()
    {
        $middleware = __NAMESPACE__ . '\\' . self::FOLDER_MIDDLEWARES . '\\' . ucfirst(strtolower($this->middlewareName)) . self::PREFIX;

        if (!class_exists($middleware)) Response::error(500, "Класс " . $middleware . " не найден");

        $this->middleware = new $middleware();
    }

    public function control()
    {
        if (!$this->middleware instanceof BaseMiddlewareInterface) Response::error(500, 'Ошибка сервера');

        if (!$this->middleware->verify()) {
            throw new MiddlewareException($this->middleware->error()[1], $this->middleware->error()[0]);
        }
    }
}