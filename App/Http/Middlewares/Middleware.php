<?php

namespace App\Http\Middlewares;

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
        $middleware = new $middleware();
        if (!$middleware->verify()) {
            Route::error(...$middleware->error());
        }
    }
}