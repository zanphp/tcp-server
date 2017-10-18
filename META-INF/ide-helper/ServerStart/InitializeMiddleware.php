<?php

namespace Zan\Framework\Network\Tcp\ServerStart;

class InitializeMiddleware
{
    private $InitializeMiddleware;

    public function __construct()
    {
        $this->InitializeMiddleware = new \ZanPHP\TcpServer\ServerStart\InitializeMiddleware();
    }

    public function bootstrap($server)
    {
        $this->InitializeMiddleware->bootstrap($server);
    }
}
