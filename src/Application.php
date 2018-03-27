<?php

namespace Pheral\Essential;

use Pheral\Essential\Container\Pool;
use Pheral\Essential\Core\Interfaces\Executable;

class Application extends Pool
{
    protected $core;
    public function __construct(Executable $core)
    {
        $this->core = $core;
    }
    public function run()
    {
        try {
            $this->core->execute();
            $this->terminate();
        } catch (\Throwable $exception) {
            skip([
                'MESSAGE' => $exception->getMessage(),
                'PLACE' => $exception->getFile() . ':' . $exception->getLine(),
                'TRACE' => PHP_EOL . $exception->getTraceAsString()
            ], true);
        }
    }
    protected function terminate()
    {
        $this->core->terminate();
    }
}
