<?php

namespace Pheral\Essential;

use App\Exceptions\ExceptionHandler;
use Pheral\Essential\Container\Pool;
use Pheral\Essential\Core\Interfaces\Executable;

class Application extends Pool
{
    protected $core;
    public function run(Executable $core)
    {
        try {
            $core->execute();
            $this->terminate($core);
        } catch (\Throwable $exception) {
            (new ExceptionHandler())->display($exception);
        }
    }
    protected function terminate(Executable $core)
    {
        $core->terminate();
    }
}
