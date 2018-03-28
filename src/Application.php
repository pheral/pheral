<?php

namespace Pheral\Essential;

use App\Exceptions\ExceptionHandler;
use Pheral\Essential\Container\Pool;
use Pheral\Essential\Core\Interfaces\Executable;
use Pheral\Essential\Exceptions\NetworkException;

class Application extends Pool
{
    protected $running = false;
    public function run(Executable $core)
    {
        try {
            if ($this->running) {
                throw new NetworkException(500, 'Application is still running');
            }
            $this->running = true;
            $core->execute();
            $this->terminate($core);
        } catch (\Throwable $exception) {
            (new ExceptionHandler())->display($exception);
        } finally {
            $this->running = false;
        }
    }
    protected function terminate(Executable $core)
    {
        $core->terminate();
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
