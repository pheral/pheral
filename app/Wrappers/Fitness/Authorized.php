<?php

namespace App\Wrappers\Fitness;

use App\Wrappers\Abstracts\Wrapper;
use Pheral\Essential\Network\Frame;

class Authorized extends Wrapper
{
    public function beforeController()
    {
        $frame = Frame::instance();
        if (!$frame->session()->get('uid')) {
            $authUrl = url()->path('/fitness/auth');
            $this->skipNextWithRedirect(redirect($authUrl));
        }
    }
}
