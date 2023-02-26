<?php

namespace Bootstrap;

use App\Http\Middlewares\Middleware;
use Zend\Diactoros\Response\EmptyResponse;

final class Kernel
{
    public function start(): void
    {
        $dispatcher = Dispatcher::return();

        if ($dispatcher === false) {
            Response::error();
        }

        if (!empty($dispatcher->middleware)) {
            foreach ($dispatcher->middleware as $middle) {
                new Middleware($middle);
            }
        }

        $this->actionDomainResponder($dispatcher);
    }

    private function actionDomainResponder(Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->controllerName;
        $action = $dispatcher->action;
        $controller = new $controllerName();

        if (empty($action)) {
            $controller(...$dispatcher->matches);
        } else {
            $controller->$action(...$dispatcher->matches);
        }
    }
}