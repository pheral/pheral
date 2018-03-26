<?php

namespace Pheral\Essential\Data;

use Pheral\Essential\Container\Factory;
use Pheral\Essential\Container\Pool;

class Server
{
    protected $data = [];
    protected $headers;
    protected $path;
    protected $isSecure;
    protected $isXmlHttpRequest;
    public function __construct()
    {
        $this->data = ${'_SERVER'};
        $this->headers = Factory::singleton('Headers', Headers::class, $this);
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
            $this->path = dirname($this->get('DOCUMENT_ROOT'));
        }
        if ($path) {
            return realpath($this->path . '/' . trim($path, '/ '));
        }
        return $this->path;
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
