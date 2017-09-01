<?php

use ZanPHP\Support\Di;

$container = \ZanPHP\Container\Container::getInstance();

$container->bind("ServerBase.TcpServer", function ($_, $args) {
    return Di::make(\ZanPHP\TcpServer\Server::class, [$args[0], $args[1]]);
});

return [];