<?php

function cleanRoute($str)
{
    return preg_replace('/(^\/)|(\/$)/', '', $str);
}

function prepareRoute($str)
{
    return '~^' . $str . '$~';
}

function view($pathTemplate, $vars = [])
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