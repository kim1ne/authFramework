<?php

namespace Bootstrap;

use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\SapiEmitter;

class Route
{
    private static array $get;
    private static array $post;
    private static array $put;
    private static array $delete;

    public static function get(string $route, $controllerAndAction): Dispatcher
    {
        $dispatcher = new Dispatcher($route, $controllerAndAction[0], $controllerAndAction[1] ?? '');
        self::$get[] = $dispatcher;
        return $dispatcher;
    }

    public static function post(string $route, $controllerAndAction): Dispatcher
    {
        $dispatcher = new Dispatcher($route, $controllerAndAction[0], $controllerAndAction[1] ?? '');
        self::$post[] = $dispatcher;
        return $dispatcher;
    }

    public static function put(string $route, $controllerAndAction): Dispatcher
    {
        $dispatcher = new Dispatcher($route, $controllerAndAction[0], $controllerAndAction[1] ?? '');
        self::$put[] = $dispatcher;
        return $dispatcher;
    }

    public static function delete(string $route, $controllerAndAction): Dispatcher
    {
        $dispatcher = new Dispatcher($route, $controllerAndAction[0], $controllerAndAction[1] ?? '');
        self::$delete[] = $dispatcher;
        return $dispatcher;
    }

    public static function getGet()
    {
        return self::$get;
    }

    public static function getPost()
    {
        return self::$post;
    }

    public static function getPut()
    {
        return self::$put;
    }

    public static function getDelete()
    {
        return self::$delete;
    }

    public static function all()
    {
        return [
            self::$delete ?? [],
            self::$post ?? [],
            self::$put ?? [],
            self::$get ?? [],
        ];
    }
}