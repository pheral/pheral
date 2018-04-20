<?php

namespace Pheral\Essential;

use App\Exceptions\ExceptionHandler;
use Pheral\Essential\Container\Pool;
use Pheral\Essential\Core\Interfaces\Executable;
use Pheral\Essential\Data\Config;
use Pheral\Essential\Exceptions\NetworkException;

class Application extends Pool
{
    /**
     * @var string $path
     */
    protected $path;

    /**
     * @var \Pheral\Essential\Data\Config
     */
    protected $config;

    protected $running = false;

    public function __construct($path = '')
    {
        parent::__construct();
        $this->path = realpath($path);
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

    protected function boot()
    {
        if ($this->running) {
            throw new NetworkException(500, 'Application is still running');
        }
        $this->running = true;
        $this->config = $this->makeSingleton('Config', Config::class);
        $this->config->load('app');
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
}
