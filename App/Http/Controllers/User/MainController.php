<?php


namespace App\Http\Controllers\User;


use App\Http\ControllerServices\User\MainControllerServices;
use App\Exceptions\UserException;
use App\Models\User;
use App\Services\Auth;
use Zend\Diactoros\ServerRequestFactory;

class MainController
{
    public function auth()
    {
        try {
            $request = ServerRequestFactory::fromGlobals();

            Auth::auth($request->getParsedBody());

            header('Location: /');
        } catch (UserException $exception) {
            echo $exception->getMessage();
        }
    }

    public function logout()
    {
        $request = ServerRequestFactory::fromGlobals();
        $cookieData = $request->getCookieParams()['auth'] ?? [];

        MainControllerServices::logout($cookieData);
    }

    public function register()
    {
        try {
            if (User::isAuth()) {
                header('Location: /');
            }

            $request = ServerRequestFactory::fromGlobals();
            $data = MainControllerServices::register($request->getParsedBody());

            $user = new User();
            $user->set('login', $data['login']);
            $user->set('password', $data['password']);
            $user->save();

            header('Location: /auth');
        } catch (UserException $exception) {
            echo $exception->getMessage();
        }
    }

    public function posts()
    {
        User::remove(48);
    }
}