<?php
/**
 * Created by PhpStorm.
 * User: xiaoniu
 * Date: 16/6/8
 * Time: 上午11:19
 */

namespace Zan\Framework\Network\Tcp\WorkerStart;

use Zan\Framework\Contract\Network\Bootable;

class InitializeServerRegister implements Bootable
{
    private $InitializeServerRegister;

    public function __construct()
    {
        $this->InitializeServerRegister = new \ZanPHP\TcpServer\WorkerStart\InitializeServerRegister();
    }

    public function bootstrap($server)
    {
        $this->InitializeServerRegister->bootstrap($server);
    }
}