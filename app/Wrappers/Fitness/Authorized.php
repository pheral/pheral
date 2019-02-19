<?php

namespace App\Wrappers\Fitness;

use App\Wrappers\Abstracts\Wrapper;

class Authorized extends Wrapper
{
    public function beforeController()
    {
        $this->skipNextWithMessage('TODO: check authorization');
    }
}
