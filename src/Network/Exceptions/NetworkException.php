<?php

namespace Pheral\Essential\Network\Exceptions;

class NetworkException extends \RuntimeException
{
    protected $httpCode;
    public function __construct($httpCode, $message, $code = 0, \Exception $previous = null)
    {
        $this->httpCode = $httpCode;
        parent::__construct($message, $code, $previous);
    }
    public function getHttpCode()
    {
        return $this->httpCode;
    }
}
