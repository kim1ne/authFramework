<?php

namespace App\Http\Controllers\User;

use App\Http\ControllerServices\User\MainControllerServices;
use App\Models\Category;
use App\Models\User;
use Bootstrap\Response;
use Bootstrap\Route;
use Zend\Diactoros\ServerRequestFactory;

class CreateController
{
    public function __invoke()
    {
        if (User::isAuth()) {
            Route::redirect(href('index'));
        }

        return view('index');
    }

    public function create()
    {
        $request = ServerRequestFactory::fromGlobals();
        $post = $request->getParsedBody();
        $data = MainControllerServices::register($post);
        $user = new User();
        $user->set('login', $data['login']);
        $user->set('password', $data['password']);
        $user->save();
        $token = $user->authorize();
        $user = $user->toArray();
        $user['token'] = $token;
        $data = [
            'status' => 'success',
            'data' => $user
        ];
        Response::json($data);
    }
}