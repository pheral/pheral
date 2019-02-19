<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Network\Output\Response;

abstract class Wrapper
{
    public function handle(callable $callNextWrapper)
    {
        //debug(static::class . '::handle($callNextWrapper); // actions before response generated');

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
