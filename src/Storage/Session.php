<?php

namespace Pheral\Essential\Storage;

class Session
{
    private static $instance;
    protected $data = [];
    protected $isRedirected;
    private function __construct()
    {
        if (!session_id()) {
            session_start();
        }
        $this->data =& ${'_SESSION'};
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
    public function get($key, $default = null)
    {
        return array_get($this->data, $key, $default);
    }
    public function set($key, $value)
    {
        array_set($this->data, $key, $value);
        return $this;
    }
    public function cut($key, $default = null)
    {
        return array_cut($this->data, $key, $default);
    }
    public function clear()
    {
        session_unset();
        return $this;
    }
    public function getCurrentUrl()
    {
        return $this->get('_url.current');
    }
    public function setCurrentUrl($url)
    {
        $this->set('_url.current', $url);
        return $this;
    }
    public function refreshRedirected()
    {
        if (is_null($this->isRedirected)) {
            $urlRedirected = $this->cut('_url.redirected');
            $this->isRedirected = $this->getCurrentUrl() === $urlRedirected;
        }
        return $this;
    }
    public function setPreviousUrl($url)
    {
        $this->set('_url.previous', $url);
        return $this;
    }
    public function getPreviousUrl()
    {
        return $this->get('_url.previous', $this->getCurrentUrl());
    }
    public function setRedirectedUrl($url)
    {
        $this->set('_url.redirected', $url);
        return $this;
    }
    public function isRedirected()
    {
        return $this->isRedirected;
    }
}
