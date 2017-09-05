<?php

namespace Zan\Framework\Network\Tcp;

use ZanPHP\Contracts\Tcp\TcpRequest;

class Request implements TcpRequest
{
    private $Request;

    public function __construct($fd, $fromId, $data, $swooleServer)
    {
        $this->Request = new \ZanPHP\TcpServer\Request($fd, $fromId, $data, $swooleServer);
    }

    public function getData()
    {
        $this->Request->getData();
    }

    public function setData($data)
    {
        $this->Request->setData($data);
    }

    public function setFd($fd)
    {
        $this->Request->setFd($fd);
    }

    public function getFd()
    {
        $this->Request->getFd();
    }

    public function setRemote($ip, $port)
    {
        $this->Request->setRemote($ip, $port);
    }

    public function setFromId($fromId)
    {
        $this->Request->setFromId($fromId);
    }

    public function setSeqNo($seqNo)
    {
        $this->Request->setSeqNo($seqNo);
    }

    public function getAttachData()
    {
        $this->Request->getAttachData();
    }

    public function getRoute()
    {
        $this->Request->getRoute();
    }

    public function getServiceName()
    {
        $this->Request->getServiceName();
    }

    public function getNovaServiceName()
    {
        $this->Request->getNovaServiceName();
    }

    public function getMethodName()
    {
        $this->Request->getMethodName();
    }

    public function getArgs()
    {
        $this->Request->getArgs();
    }

    public function getRemote()
    {
        $this->Request->getRemote();
    }

    public function getRemotePort()
    {
        $this->Request->getRemotePort();
    }

    public function getFromId()
    {
        $this->Request->getFromId();
    }

    public function getSeqNo()
    {
        $this->Request->getSeqNo();
    }

    public function getIsHeartBeat()
    {
        $this->Request->getIsHeartBeat();
    }

    public function getStartTime()
    {
        $this->Request->getStartTime();
    }

    public function setStartTime()
    {
        $this->Request->setStartTime();
    }

    public function getRemoteIp()
    {
        $this->Request->getRemoteIp();
    }

    public function setRemoteIp($remoteIp)
    {
        $this->Request->setRemoteIp($remoteIp);
    }

    public function getGenericServiceName()
    {
        $this->Request->getGenericServiceName();
    }

    public function getGenericMethodName()
    {
        $this->Request->getGenericMethodName();
    }

    public function getGenericRoute()
    {
        $this->Request->getGenericRoute();
    }

    public function getRpcContext()
    {
        $this->Request->getRpcContext();
    }

    public function isGenericInvoke()
    {
        $this->Request->isGenericInvoke();
    }

    public function decode()
    {
        $this->Request->decode() ;
    }
}