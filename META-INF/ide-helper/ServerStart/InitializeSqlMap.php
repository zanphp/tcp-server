<?php

namespace Zan\Framework\Network\Tcp\ServerStart;

use Zan\Framework\Contract\Network\Bootable;

class InitializeSqlMap implements Bootable
{
    private $InitializeSqlMap;

    public function __construct()
    {
        $this->InitializeSqlMap = new \ZanPHP\TcpServer\ServerStart\InitializeSqlMap();
    }

    public function bootstrap($server)
    {
        $this->InitializeSqlMap->bootstrap($server);
    }
}