<?php

namespace Zan\Framework\Network\Tcp;

use \swoole_server as SwooleServer;

class RequestHandler
{
    private $RequestHandler;

    public function __construct()
    {
        $this->RequestHandler = new \ZanPHP\TcpServer\RequestHandler();
    }

    public function handle(SwooleServer $swooleServer, $fd, $fromId, $data)
    {
        $this->RequestHandler->handle($swooleServer, $fd, $fromId, $data);
    }

    public static function handleException($middleware, $response, $t)
    {
        \ZanPHP\TcpServer\RequestHandler::handleException($middleware, $response, $t);
    }

    public function handleRequestFinish()
    {
        $this->RequestHandler->handleRequestFinish();
    }

    public function handleTimeout()
    {
        $this->RequestHandler->handleTimeout();
    }
}
