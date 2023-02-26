<?php

namespace App\Http\Controllers\User;

use App\Http\ControllerServices\User\MainControllerServices;
use App\Models\Category;
use App\Models\User;
use App\Services\Auth;
use Bootstrap\Response;
use Bootstrap\Route;
use Zend\Diactoros\ServerRequestFactory;

class AuthController
{
    public function __invoke()
    {
        $request = ServerRequestFactory::fromGlobals();
        $post = $request->getParsedBody();

        if (empty($post)) Response::error(400, 'Данные пустые');

        $user = Auth::auth($post);
        $token = $user->authorize();

        $user = $user->toArray();
        $user['token'] = $token;

        Response::json(['status' => 'success', 'data' => $user]);
    }
}