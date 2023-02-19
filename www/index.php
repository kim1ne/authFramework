<?php

use App\Exceptions\WrongExceptionInterface;

try {
    chdir(dirname(__DIR__));

    require 'vendor/autoload.php';

    require 'functions.php';

    require 'routes.php';

    $app = new Bootstrap\Kernel();
    $app->start();

} catch (WrongExceptionInterface $exception) {
    $exception->wrong();
}