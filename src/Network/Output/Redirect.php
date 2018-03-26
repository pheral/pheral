<?php

namespace Pheral\Essential\Network\Output;

use Pheral\Essential\Network\Frame;

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
    public static function make($url = '', $status = 302)
    {
        return new static($url, $status);
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
        $target = Frame::instance()->getPreviousUrl();
        return $this->setUrl($target);
    }
    public function send()
    {
        if (!$url = $this->getUrl()) {
            return ;
        }
        $location = $url;
        if (!parse_url($url, PHP_URL_HOST)) {
            $host = Frame::instance()->getHost();
            $location = $host . '/' . ltrim($location, '/');
        }
        if (!parse_url($url, PHP_URL_SCHEME)) {
            $protocol = Frame::instance()->getProtocol();
            $location = $protocol . '://' . ltrim($location, '://');
        }
        $status = $this->getStatus();
        header("Location: {$location}", true, $status);
    }
}
