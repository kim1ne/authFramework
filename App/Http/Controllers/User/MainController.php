<?php


namespace App\Http\Controllers\User;


use App\Exceptions\UserException;
use App\Http\ControllerServices\User\MainControllerServices;
use App\Models\Photo;
use App\Models\User;
use App\Services\Auth;
use App\Services\Db\DataManager;
use Bootstrap\Response;
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
            $data = MainControllerServices::register($request->getParsedBody() ?? []);

            $user = new User();
            $user->set('login', $data['login']);
            $user->set('password', $data['password']);
            $user->save();

            view('main', ['status' => 'success', 'data' => $user]);
        } catch (UserException $exception) {
            Response::error(200, $exception->getMessage());
        }
    }

    public function posts()
    {
        $dataManager = new DataManager([User::getTableName() => 'user'], ['id' => 'misha', 'login']);
        $dataManager->join([Photo::getTableName() => 'user_photo'], ['user_id' => 'user.id'], [], DataManager::LEFT_JOIN);
        $dataManager->limit(2);
        debug($dataManager->build());
    }
}