<?php

namespace Bootstrap;

use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

final class Request
{
    public ServerRequest $request;
    private array $outputStreamMethods = [
        'delete',
        'put',
        'patch',
    ];
    private bool $isBody = false;

    public function __construct()
    {
        $this->request = ServerRequestFactory::fromGlobals();
    }

    public function getMethod()
    {
        if (!empty($this->request->getParsedBody()['_method'])) {
            $this->isBody = true;
            return $this->request->getParsedBody()['_method'];
        }
        return strtolower($this->request->getMethod());
    }


    public function getParamsRequest()
    {
        $method = $this->getMethod();

        if ($method === 'post' || $this->isBody === true) {
            $request = $this->request->getParsedBody();
        }
        if ($method === 'get') {
            $request = $this->request->getQueryParams();
        }
        if (in_array($method, $this->outputStreamMethods) && $this->isBody === false) {
            $request = $this->output();
        }

        return $request;
    }

    private function output()
    {
        $request = $this->request->getBody()->getContents();
        return $request = json_decode($request, true);
    }
}