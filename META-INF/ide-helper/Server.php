<?php

namespace Zan\Framework\Network\Tcp;

use swoole_server as SwooleServer;
use ZanPHP\ServerBase\ServerBase;

class Server extends ServerBase
{
    private $Server;

    public function __construct()
    {
        $this->Server = new \ZanPHP\TcpServer\Server();
    }

    public function setSwooleEvent()
    {
        $this->Server->setSwooleEvent();
    }

    protected function init()
    {
        $this->Server->init();
    }

    public function onConnect()
    {
        $this->Server->onConnect();
    }

    public function onClose()
    {
        $this->Server->onClose();
    }

    public function onStart($swooleServer)
    {
        $this->Server->onStart($swooleServer);
    }

    public function onShutdown($swooleServer)
    {
        $this->Server->onShutdown($swooleServer);
    }

    public function onWorkerStart($swooleServer, $workerId)
    {
        $this->Server->onWorkerStart($swooleServer, $workerId);
    }

    public function onWorkerStop($swooleServer, $workerId)
    {
        $this->Server->onWorkerStop($swooleServer, $workerId);
    }

    public function onWorkerError($swooleServer, $workerId, $workerPid, $exitCode, $sigNo)
    {
        $this->Server->onWorkerError($swooleServer, $workerId, $workerPid, $exitCode, $sigNo);
    }

    public function onPacket(SwooleServer $swooleServer, $data, array $clientInfo)
    {
        $this->Server->onPacket($swooleServer, $data, $clientInfo);
    }

    public function onReceive(SwooleServer $swooleServer, $fd, $fromId, $data)
    {
        $this->Server->onReceive($swooleServer, $fd, $fromId, $data);
    }
}
