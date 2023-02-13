<?php

namespace App\Middlewares;

use Bootstrap\Route;

class Middleware
{
    const FOLDER_WARES = 'Wares';

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
        $prefix = basename(str_replace('\\', '/', get_class()));
        $middleware = __NAMESPACE__ . '\\' . self::FOLDER_WARES . '\\' . ucfirst(strtolower($this->middleware)) . $prefix;
        $middleware = new $middleware();
        if (!$middleware->verify()) {
            Route::error($middleware->errorCode());
        }
    }
}