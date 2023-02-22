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

function view($pathTemplate, $data)
{
    $request = ServerRequestFactory::fromGlobals();
    $header = $request->getHeaders();
    preg_match('~application\/(\w+)~', $header['accept'][0], $matches);

    if (is_array($matches)) $matches = $matches[0];

    switch ($matches) {
        case 'application/xhtml':
            extract($data['vars']);
            ob_start();
            require 'templates/' . $pathTemplate . '.php';
            $html = ob_get_clean();
            return $html;
        default :
            $json = [];

            $json['status'] = $data['status'];
            if (!empty($data['message'])) {
                $json['message'] = $data['message'];
            }
            if (!empty($data['data'])) {
                $json['data'] = $data['data'];
            }

            $response = new \Zend\Diactoros\Response\JsonResponse($json);
            return $response;
    }
}

function debug($str)
{
    echo '<pre>';
    print_r($str);
    echo '</pre>';
}

function href(string $name)
{
    $packs = \Bootstrap\Route::all();
    foreach ($packs as $pack) {
        foreach ($pack as $route) {
            if (cleanRoute($route->name) === $name) {
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