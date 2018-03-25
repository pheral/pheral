<?php

namespace Pheral\Essential\Network;

use Pheral\Essential\Data\Pool;

class Redirect
{
    protected $scheme;
    protected $host;
    protected $url;
    protected $status;
    protected $options;
    public function __construct($url = '', $status = 302)
    {
        if ($url) {
            $this->setUrl($url);
        }
        if ($status) {
            $this->setStatus($status);
        }
    }
    protected function getRequest(): Request
    {
        return Pool::get('Request');
    }
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
    public function getUrl()
    {
        return $this->url;
    }
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function back()
    {
        $target = $this->getRequest()->getPreviousUrl();
        return $this->setUrl($target);
    }
    public function send()
    {
        if (!$url = $this->getUrl()) {
            return ;
        }
        $location = $url;
        if (!parse_url($url, PHP_URL_HOST)) {
            $host = $this->getRequest()->getHost();
            $location = $host . '/' . ltrim($location, '/');
        }
        if (!parse_url($url, PHP_URL_SCHEME)) {
            $protocol = $this->getRequest()->getProtocol();
            $location = $protocol . '://' . ltrim($location, '://');
        }
        $status = $this->getStatus();
        header("Location: {$location}", true, $status);
    }
}
