<?php

namespace Pheral\Essential\Network\Routing;

use Pheral\Essential\Network\Frame;

class Url
{
    private static $instance;
    private function __construct()
    {
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
    public function path(string $path = '')
    {
        $frame = Frame::instance();
        $baseUrl = $frame->getProtocol() . '://' . $frame->getHost();
        if (!$path) {
            return $baseUrl;
        }
        return $baseUrl . '/' . trim($path, '/');
    }
}
