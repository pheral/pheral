<?php

namespace Pheral\Essential;

use App\Exceptions\ExceptionHandler;
use Pheral\Essential\Core\Interfaces\Executable;
use Pheral\Essential\Storage\Config;
use Pheral\Essential\Exceptions\NetworkException;

class Application
{
    private static $instance;

    protected $running = false;

    protected $path;

    /**
     * @var \Pheral\Essential\Storage\Config
     */
    protected $config;

    private function __construct($path = '')
    {
        $this->path = realpath($path);
    }

    public static function instance($path = '')
    {
        if (!self::$instance) {
            self::$instance = new self($path);
        }
        return self::$instance;
    }

    protected function boot()
    {
        if ($this->running) {
            $this->error('Application is still running');
        }
        $this->running = true;
        $this->config = Config::instance()->load('app');
    }

    public function run(Executable $core)
    {
        try {
            $this->boot();
            $core->execute();
            $this->terminate($core);
        } catch (\Throwable $exception) {
            (new ExceptionHandler())->display($exception);
        }
    }

    protected function terminate(Executable $core)
    {
        $core->terminate();
        $this->running = false;
    }

    public function path($path = '')
    {
        if ($path) {
            return realpath($this->path . '/' . trim($path, '/ '));
        }
        return $this->path;
    }

    public function config($key = '', $default = null)
    {
        if ($key) {
            return $this->config->get($key, $default);
        }
        return $this->config;
    }

    /**
     * @param $message
     * @param $code
     * @throws \Pheral\Essential\Exceptions\NetworkException
     */
    public function error($message, $code = 500)
    {
        throw new NetworkException($code, $message);
    }
}
