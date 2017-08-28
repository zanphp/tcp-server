<?php

namespace Zan\Framework\Network\Tcp;

use Thrift\Exception\TApplicationException;
use ZanPHP\Contracts\Tcp\TcpRequest;
use ZanPHP\Contracts\Codec\Codec;
use ZanPHP\Exception\Codec\CodecException;
use ZanPHP\NovaCodec\NovaPDU;
use ZanPHP\NovaGeneric\GenericRequestCodec as GenericRequestCodecA;
use ZanPHP\ThriftSerialization\ThriftSerializable;

class Request implements TcpRequest
{
    private $swooleServer;
    private $data;
    private $route;
    private $serviceName;
    private $novaServiceName;
    private $methodName;
    private $args;
    private $fd;

    private $remoteIp;
    private $remotePort;
    private $fromId;
    private $seqNo;

    private $startTime;
    private $isHeartBeat = false;

    private $isGenericInvoke = false;
    private $genericServiceName;
    private $genericMethodName;
    private $genericRoute;

    /**
     * @var RpcContext
     */
    private $rpcContext;

    public function __construct($fd, $fromId, $data, $swooleServer)
    {
        $this->fd = $fd;
        $this->fromId = $fromId;
        $this->data = $data;
        $this->swooleServer = $swooleServer;
        $this->rpcContext = new RpcContext();
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setFd($fd)
    {
        $this->fd = $fd;
    }

    public function getFd()
    {
        return $this->fd;
    }

    public function setRemote($ip, $port)
    {
        $this->remoteIp = $ip;
        $this->remotePort = $port;
    }

    public function setFromId($fromId)
    {
        $this->fromId = $fromId;
    }

    public function setSeqNo($seqNo)
    {
        $this->seqNo = $seqNo;
    }

    public function getAttachData()
    {
        return $this->rpcContext->packNovaAttach();
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }

    public function getNovaServiceName()
    {
        return $this->novaServiceName;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function getRemote()
    {
        return [
            'ip' =>$this->remoteIp,
            'port' => $this->remotePort,
        ];
    }

    public function getRemotePort()
    {
        return $this->remotePort;
    }

    public function getFromId()
    {
        return $this->fromId;
    }

    public function getSeqNo()
    {
        return $this->seqNo;
    }

    public function getIsHeartBeat()
    {
        return $this->isHeartBeat;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime()
    {
        $this->startTime = microtime(true);
    }

    public function getRemoteIp()
    {
        return $this->remoteIp;
    }

    public function setRemoteIp($remoteIp)
    {
        $this->remoteIp = $remoteIp;
    }

    public function getGenericServiceName()
    {
        return $this->genericServiceName;
    }

    public function getGenericMethodName()
    {
        return $this->genericMethodName;
    }

    public function getGenericRoute()
    {
        return $this->genericRoute;
    }

    public function getRpcContext()
    {
        return $this->rpcContext;
    }

    public function isGenericInvoke()
    {
        return $this->isGenericInvoke;
    }

    private function formatRoute()
    {
        $serviceName = ucwords($this->serviceName, '.');
        $this->novaServiceName = str_replace('.','\\',$serviceName);

        $path = '/'. str_replace('.', '/', $serviceName) . '/';
        $this->route = $path . $this->methodName;
    }

    private function decodeArgs()
    {
        $thrift = new ThriftSerializable();
        $thrift->service = $this->novaServiceName;
        $thrift->method = $this->methodName;
        $thrift->struct = $this->args;
        $thrift->side = ThriftSerializable::SERVER;
        $this->args = $thrift->unserialize();
    }

    public function decode() {
        /** @var Codec $codec */
        $codec = make("codec:nova");

        try {
            $pdu = $codec->decode($this->data);
            if ($pdu instanceof NovaPDU) {
                return $this->decodeNovaRequest($pdu, $codec);
            }
        } catch (CodecException $e) {
            throw new TApplicationException("nova_decode fail");
        }
    }

    private function decodeNovaRequest(NovaPDU $pdu, Codec $codec)
    {
        $this->serviceName = trim($pdu->serviceName);
        $this->methodName = trim($pdu->methodName);
        $this->args = $pdu->body;
        $this->remoteIp = $pdu->ip;
        $this->remotePort = $pdu->port;
        $this->seqNo = $pdu->seqNo;
        $this->rpcContext->unpackNovaAttach($pdu->attach);

        if($this->serviceName === "com.youzan.service.test") {
            if ($this->methodName === "ping") {
                $this->isHeartBeat = true;
                $pdu->methodName = "pong";
                $pdu->attach = "";
                $pdu->body = "";
                return $codec->encode($pdu);
            }
        }

        $this->isGenericInvoke = GenericRequestCodecA::isGenericService($this->serviceName);
        if ($this->isGenericInvoke) {
            $this->initGenericInvoke($this->serviceName);

            if (!method_exists($this->swooleServer, "stats")) {
                return null;
            }
            if ($this->genericServiceName == "com.youzan.service.test") {
                if ($this->genericMethodName === "stats") {
                    $content = GenericRequestCodecA::encode($this->genericServiceName, $this->genericMethodName, $this->swooleServer->stats());

                    $thrift = new ThriftSerializable();
                    $thrift->service = $this->novaServiceName;
                    $thrift->method = $this->methodName;
                    $thrift->struct = $content;
                    $thrift->side = ThriftSerializable::SERVER;
                    $content = $thrift->serialize();

                    $this->isHeartBeat = true;
                    $pdu->methodName = "stats";
                    $pdu->attach = "";
                    $pdu->body = $content;
                    return $codec->encode($pdu);
                }
            }
            return null;
        }

        $this->formatRoute();
        $this->decodeArgs();
    }


    private function initGenericInvoke($serviceName)
    {
        $this->novaServiceName = str_replace('.', '\\', ucwords($this->serviceName, '.'));
        $genericRequest = GenericRequestCodecA::decode($this->novaServiceName, $this->methodName, $this->args);
        $this->genericServiceName = $genericRequest->serviceName;
        $this->genericMethodName = $genericRequest->methodName;
        $this->args = $genericRequest->methodParams;
        $this->route = '/'. str_replace('.', '/', $serviceName) . '/' . $this->methodName;
        $this->genericRoute = '/'. str_replace('\\', '/', $this->genericServiceName) . '/' . $this->genericMethodName;

        // NOTICE: java-nova框架使用async的参数, 在java应用间表示调用发方式
        // php无用, 且通过nova透传调用改参数调用java会变成异步调用, 此处删除
        // 卡门其他透传参数暂时保留
        $this->rpcContext->set("async", null);
    }
}