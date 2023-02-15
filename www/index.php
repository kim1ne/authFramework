<?php

use App\Http\Middlewares\Exceptions\MiddlewareException;

try {
    chdir(dirname(__DIR__));

    require 'vendor/autoload.php';

    require 'functions.php';

    require 'routes.php';

    require 'dev.php';

    $app = new Bootstrap\Kernel();
    $app->start();
} catch (MiddlewareException $exception) {
    $exception->wrong();
}