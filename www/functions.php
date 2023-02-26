<?php

use Zend\Diactoros\ServerRequestFactory;

function cleanRoute($str)
{
    return preg_replace('/(^\/)|(\/$)/', '', $str);
}

function prepareRoute($str)
{
    return '~^' . $str . '$~';
}

function view(string $pathTemplate, array $vars = []): string
{
    extract($vars);
    return require 'templates/' . $pathTemplate . '.php';
}

function debug($str)
{
    echo '<pre>';
    print_r($str);
    echo '</pre>';
}

function href(string $name, array $match = null)
{
    $packs = \Bootstrap\Route::all();
    foreach ($packs as $pack) {
        foreach ($pack as $route) {
            if (cleanRoute($route->name) === $name) {
                if (!empty($match)) {
                    foreach ($match as $pattern => $v) {
                        return str_replace($pattern, $v, $route->route);
                    }
                }
                return $route->route;
            }
        }
    }
    return '';
}

function jsonSerialize()
{
    $request = ServerRequestFactory::fromGlobals();
    $accepted = $request->getHeaders()['accept'][0];
    $stringFind = strpos('application/json', $accepted);
    return is_int($stringFind);
}