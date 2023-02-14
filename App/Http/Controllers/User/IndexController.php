<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Services\Db\Builder;
use Zend\Diactoros\ServerRequestFactory;

class IndexController
{
    public function index()
    {
        if (!User::isAuth()) {
            return view('index');
        }

        return view('main', ['user' => User::current()]);
    }

    public function auth()
    {
        if (User::isAuth()) {
            header('Location: /');
        }

        return view('auth');
    }
}