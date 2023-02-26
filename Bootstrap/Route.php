<?php

namespace Bootstrap;

use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\SapiEmitter;

final class Route
{
    private static array $get = [];
    private static array $post = [];
    private static array $put = [];
    private static array $delete = [];

    private static function createDispatch(string $route, $controllerAndAction): Dispatcher
    {
        if (is_array($controllerAndAction)) {
            $dispatcher = new Dispatcher($route, $controllerAndAction[0], $controllerAndAction[1]);
        } else {
            $dispatcher = new Dispatcher($route, $controllerAndAction);
        }
        return $dispatcher;
    }

    public static function get(string $route, $controllerAndAction): Dispatcher
    {
        $dispatcher = self::createDispatch($route, $controllerAndAction);
        self::$get[] = $dispatcher;
        return $dispatcher;
    }

    public static function post(string $route, $controllerAndAction): Dispatcher
    {
        $dispatcher = self::createDispatch($route, $controllerAndAction);
        self::$post[] = $dispatcher;
        return $dispatcher;
    }

    public static function put(string $route, $controllerAndAction): Dispatcher
    {
        $dispatcher = self::createDispatch($route, $controllerAndAction);
        self::$put[] = $dispatcher;
        return $dispatcher;
    }

    public static function delete(string $route, $controllerAndAction): Dispatcher
    {
        $dispatcher = self::createDispatch($route, $controllerAndAction);
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

    public static function redirect(string $url, int $responseCode = 301) {
        header('Location: ' . $url, true, $responseCode);
    }

    public static function group(array $settings, \Closure $function)
    {
        $generate = function () use ($function) {
            return $function();
        };
        $generate();
    }
}