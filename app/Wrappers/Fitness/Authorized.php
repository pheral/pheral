<?php

namespace App\Wrappers\Fitness;

use App\Wrappers\Abstracts\Wrapper;

class Authorized extends Wrapper
{
    public function beforeController()
    {
        debug_trace('@todo check authorization');
        $this->skipNext();
    }
}
