<?php

namespace Bootstrap;

use App\Http\Middlewares\Middleware;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;

final class Kernel
{
    public function start(): void
    {
        $dispatcher = Dispatcher::return();

        if ($dispatcher === true) {
            Response::error(404, 'Это файл');
        }

        if ($dispatcher === false) {
            Response::error(404, 'Страница не найдена');
        }

        if (isset($dispatcher->middleware)) {
            new Middleware($dispatcher->middleware);
        }

        $this->actionDomainResponder($dispatcher->controllerName, $dispatcher->action, $dispatcher->matches);
    }

    private function actionDomainResponder(string $controllerName, string $action, array $matches)
    {
        $controller = new $controllerName();
        $controller->$action(...$matches);
    }

    private function emit(ResponseInterface $response)
    {
        $emmiter = new SapiEmitter();
        $emmiter->emit($response);
    }
}