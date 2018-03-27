<?php

namespace Pheral\Essential\Data;

use Pheral\Essential\Container\Pool;

class Server
{
    protected $data = [];
    protected $headers;
    protected $referer;
    protected $path;
    protected $requestMethod;
    protected $isXmlHttpRequest;
    protected $isSecure;
    public function __construct()
    {
        $this->data = ${'_SERVER'};

        Pool::singleton('Headers', Headers::class, [$this->data]);
        $this->headers = Headers::instance();
    }
    public static function instance(): Server
    {
        return Pool::get('Server');
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
    public function path($path = '')
    {
        if (is_null($this->path)) {
            $this->path = dirname($this->getDocumentRoot());
        }
        if ($path) {
            return realpath($this->path . '/' . trim($path, '/ '));
        }
        return $this->path;
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
