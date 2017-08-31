<?php

return [
    \ZanPHP\TcpServer\ServerStart\InitializeMiddleware::class => \Zan\Framework\Network\Tcp\ServerStart\InitializeMiddleware::class,
    \ZanPHP\TcpServer\ServerStart\InitializeSqlMap::class => \Zan\Framework\Network\Tcp\ServerStart\InitializeSqlMap::class,

    \ZanPHP\TcpServer\WorkerStart\InitializeServerRegister::class => \Zan\Framework\Network\Tcp\WorkerStart\InitializeServerRegister::class,

    \ZanPHP\TcpServer\Dispatcher::class => \Zan\Framework\Network\Tcp\Dispatcher::class,
    \ZanPHP\TcpServer\Request::class => \Zan\Framework\Network\Tcp\Request::class,
    \ZanPHP\TcpServer\RequestHandler::class => \Zan\Framework\Network\Tcp\RequestHandler::class,
    \ZanPHP\TcpServer\RequestTask::class => \Zan\Framework\Network\Tcp\RequestTask::class,
    \ZanPHP\TcpServer\Response::class => \Zan\Framework\Network\Tcp\Response::class,
    \ZanPHP\TcpServer\ResponseEntity::class => \Zan\Framework\Network\Tcp\ResponseEntity::class,
    \ZanPHP\TcpServer\Server::class => \Zan\Framework\Network\Tcp\Server::class,

];