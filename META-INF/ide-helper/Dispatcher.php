<?php

namespace Zan\Framework\Network\Tcp;

use ZanPHP\Coroutine\Context;
use ZanPHP\TcpServer\Request;

class Dispatcher
{
    private $Dispatcher;

    public function __construct()
    {
        $this->Dispatcher = new \ZanPHP\TcpServer\Dispatcher();
    }

    public function dispatch(Request $request, Context $context)
    {
        $this->Dispatcher->dispatch($request, $context);
    }

}