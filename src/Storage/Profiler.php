<?php

namespace Pheral\Essential\Storage;

use Pheral\Essential\Storage\Profiler\DBProfile;

class Profiler
{
    private static $instance;
    protected $database;
    private function __construct()
    {
        $this->database = new DBProfile();
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
    public function database(): DBProfile
    {
        return $this->database;
    }
}
