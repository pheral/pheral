<?php

namespace Pheral\Essential;

use Pheral\Essential\Core\Interfaces\Executable;

class Application
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
            debug([
                'MESSAGE' => $exception->getMessage(),
                'PLACE' => $exception->getFile() . ':' . $exception->getLine(),
                'TRACE' => PHP_EOL . $exception->getTraceAsString()
            ]);
        }
    }
    protected function terminate()
    {
        $this->core->terminate();
    }
}
