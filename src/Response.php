<?php

namespace ZanPHP\TcpServer;

use swoole_server as SwooleServer;
use ZanPHP\Contracts\Network\Response as BaseResponse;
use ZanPHP\Contracts\Codec\Codec;
use ZanPHP\Exception\Codec\CodecException;
use ZanPHP\NovaCodec\NovaPDU;
use ZanPHP\NovaGeneric\GenericRequestCodec as GenericRequestCodecY;
use ZanPHP\ThriftSerialization\ThriftSerializable;

class Response implements BaseResponse
{
    /**
     * @var SwooleServer
     */
    private $swooleServer;

    /**
     * @var Request
     */
    private $request;

    private $exception;

    /**
     * @var Codec
     */
    private $codec;

    public function __construct(SwooleServer $swooleServer, Request $request)
    {
        $this->swooleServer = $swooleServer;
        $this->request = $request;
        $this->codec = make("codec:nova");
    }

    public function getSwooleServer()
    {
        return $this->swooleServer;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function end($content='')
    {
        $this->send($content);
    }

    /**
     * @param $e \Exception
     */
    public function sendException($e)
    {
        $this->exception = $e->getMessage();
        $serviceName = $this->request->getServiceName();
        $novaServiceName = $this->request->getNovaServiceName();
        $methodName  = $this->request->getMethodName();

        // 泛化调用不透传任何异常, 直接打包发送
        if ($this->request->isGenericInvoke()) {
            return $this->send($e);
        }


        $thrift = new ThriftSerializable();
        $thrift->service = $novaServiceName;
        $thrift->method = $methodName;
        $thrift->structEx = $e;
        $thrift->side = ThriftSerializable::SERVER;
        $content = $thrift->serialize();

        $this->sendNovaResponse($serviceName, $methodName, $content);
    }

    public function send($content)
    {
        $serviceName = $this->request->getServiceName();
        $novaServiceName = $this->request->getNovaServiceName();
        $methodName  = $this->request->getMethodName();

        if ($this->request->isGenericInvoke()) {
            $content = GenericRequestCodecY::encode(
                $this->request->getGenericServiceName(),
                $this->request->getGenericMethodName(), $content);
        }

        $thrift = new ThriftSerializable();
        $thrift->service = $novaServiceName;
        $thrift->method = $methodName;
        $thrift->struct = $content;
        $thrift->side = ThriftSerializable::SERVER;
        $content = $thrift->serialize();

        $this->sendNovaResponse($serviceName, $methodName, $content);
    }

    private function sendNovaResponse($serviceName, $methodName, $content)
    {
        $remote = $this->request->getRemote();

        $pdu = new NovaPDU();
        $pdu->serviceName = $serviceName;
        $pdu->methodName = $methodName;
        $pdu->ip = $remote["ip"];
        $pdu->port = $remote["port"];
        $pdu->seqNo = $this->request->getSeqNo();
        $pdu->attach = $this->request->getAttachData();
        $pdu->body = $content;

        try {
            $outputBuffer = $this->codec->encode($pdu);
            $swooleServer = $this->getSwooleServer();
            $result = $swooleServer->send(
                $this->request->getFd(),
                $outputBuffer
            );
            if ($result !== true) {
                sys_error("send nova response failed");
            }
        } catch (CodecException $e) {
            echo_exception($e);
        }
    }
}
