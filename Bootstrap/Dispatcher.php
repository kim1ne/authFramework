<?php


namespace Bootstrap;


use Zend\Diactoros\ServerRequestFactory;

final class Dispatcher
{
    public string $route;
    public string $controllerName;
    public string $action;
    public array $matches;
    public array $middleware;
    public ?string $name;

    /**
     * Dispatcher constructor.
     * @param string $route
     * @param string $controllerName
     * @param string $action
     */
    public function __construct(string $route, string $controllerName, string $action)
    {
        $this->route = $route;
        $this->controllerName = $controllerName;
        $this->action = $action;
        $this->middleware = [];
        $this->name = null;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public static function return(): bool|self
    {
        $request = ServerRequestFactory::fromGlobals();
        $uri = cleanRoute($request->getUri()->getPath());
        $method = 'get' . ucfirst(strtolower($request->getMethod()));

        $selfObjects = Route::$method();
        foreach ($selfObjects as $self) {
            /**
             * @param Dispatcher $self
             */
            $route = prepareRoute(cleanRoute($self->route));
            preg_match($route, $uri, $matches);
            if (!empty($matches)) {
                unset($matches[0]);
                $self->matches = $matches;
                return $self;
            }
        }
        return false;
    }

    public function middleware($middleware): self
    {
        if (is_string($middleware)) $this->middleware[] = $middleware;
        if (is_array($middleware)) $this->middleware = $middleware;
        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}