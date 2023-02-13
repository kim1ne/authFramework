<?php

use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

$response = new Response();
$response = $response->withHeader('X-Developer', 'Kim_1ne');
$emmiter = new SapiEmitter();
$emmiter->emit($response);