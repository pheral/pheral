<?php

namespace Pheral\Essential\Storage;

class Server
{
    private static $instance;
    protected $data = [];
    protected $headers;
    protected $referer;
    protected $path;
    protected $requestMethod;
    protected $isXmlHttpRequest;
    protected $isSecure;
    private function __construct()
    {
        $this->data = ${'_SERVER'};
        $this->headers = Headers::instance($this->data);
    }
    private function __clone()
    {
    }
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function all(): array
    {
        return $this->data;
    }
    public function has($key): bool
    {
        return array_has($this->data, $key);
    }
    public function get($key = '', $default = null)
    {
        return array_get($this->data, $key, $default);
    }
    public function headers(): Headers
    {
        return $this->headers;
    }
    public function getDocumentRoot()
    {
        return $this->get('DOCUMENT_ROOT');
    }
    public function getQueryString()
    {
        return $this->get('QUERY_STRING');
    }
    public function getRequestUri()
    {
        return $this->get('REQUEST_URI');
    }
    public function getHost()
    {
        return $this->headers->getHost();
    }
    public function getReferer()
    {
        return $this->headers->getReferer();
    }
    public function getRequestMethod()
    {
        if (is_null($this->requestMethod)) {
            $this->requestMethod = strtoupper($this->get('REQUEST_METHOD', 'GET'));
            $methodOverride = $this->get('HTTP_X_METHOD_OVERRIDE');
            if ($this->requestMethod === 'POST' && $methodOverride) {
                $this->requestMethod = strtoupper($methodOverride);
            }
        }
        return $this->requestMethod;
    }
    public function isSecure(): bool
    {
        if (is_null($this->isSecure)) {
            $isHttpsOn = $this->has('HTTPS') && $this->get('HTTPS') !== 'off';
            $isHttpsPort = $this->get('SERVER_PORT') === 443;
            $this->isSecure = $isHttpsOn || $isHttpsPort;
        }
        return $this->isSecure;
    }
    public function isXmlHttpRequest(): bool
    {
        if (is_null($this->isXmlHttpRequest)) {
            $xmlHttpRequestedWith = $this->get('HTTP_X_REQUESTED_WITH', '');
            $this->isXmlHttpRequest = strtoupper($xmlHttpRequestedWith) === 'XMLHTTPREQUEST';
        }
        return $this->isXmlHttpRequest;
    }
}
