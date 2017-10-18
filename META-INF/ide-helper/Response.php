<?php

namespace Zan\Framework\Network\Tcp;

use swoole_server as SwooleServer;
use ZanPHP\Contracts\Network\Response as BaseResponse;
use ZanPHP\TcpServer\Request;

class Response implements BaseResponse
{
    private $Response;

    public function __construct(SwooleServer $swooleServer, Request $request)
    {
        $this->Response = new \ZanPHP\TcpServer\Response($swooleServer, $request);
    }

    public function getSwooleServer()
    {
        $this->Response->getSwooleServer();
    }

    public function getException()
    {
        $this->Response->getException();
    }

    public function end($content='')
    {
        $this->Response->end($content);
    }

    public function sendException($e)
    {
        $this->Response->sendException($e);
    }

    public function send($content)
    {
        $this->Response->send($content);
    }

}
