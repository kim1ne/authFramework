<?php

namespace Bootstrap;

use App\Http\Middlewares\Middleware;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\SapiEmitter;

final class Kernel
{
    const X_DEVELOPER = 'Kim_1ne';

    private \Zend\Diactoros\Response $response;

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

        $this->emit();
    }

    private function actionDomainResponder(Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->controllerName;
        $action = $dispatcher->action;
        $controller = new $controllerName();
        $response = $controller->$action(...$dispatcher->matches);

        if ($response instanceof \Zend\Diactoros\Response) {
            $this->response = $response->withHeader('X-Developer', self::X_DEVELOPER);
        } else {
            $this->response = new EmptyResponse();
        }
    }

    private function emit()
    {
        $emit = new SapiEmitter();
        $emit->emit($this->response);
    }
}