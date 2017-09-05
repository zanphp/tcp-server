<?php

namespace Zan\Framework\Network\Tcp;

use ZanPHP\ServerBase\Middleware\MiddlewareManager;
use ZanPHP\Coroutine\Context;
use ZanPHP\TcpServer\Request;
use ZanPHP\TcpServer\Response;

class RequestTask
{
    private $RequestTask;

    public function __construct(Request $request, Response $response, Context $context, MiddlewareManager $middlewareManager)
    {
        $this->RequestTask = new \ZanPHP\TcpServer\RequestTask($request, $response, $context, $middlewareManager);
    }

    public function run()
    {
        $this->RequestTask->run();
    }
}