<?php

namespace App\Wrappers\Fitness;

use App\Wrappers\Abstracts\Wrapper;
use Pheral\Essential\Network\Output\Response;

class Authorized extends Wrapper
{
    public function handle(callable $callNextWrapper)
    {
        //debug(static::class . '::handle($callNextWrapper); // actions before response generated');
        debug('@todo check authorization');

        $response = $callNextWrapper();

        //debug(static::class . '::handle($callNextWrapper); // actions after response generated');

        return $response;
    }

    public function terminate(Response $response)
    {
        //debug(static::class . '::terminate($callNextWrapper); // actions after response send');
        ignore($response);
    }
}
