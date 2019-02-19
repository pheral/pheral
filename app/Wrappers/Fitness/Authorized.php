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
            $flushUrl = $frame->getCurrentUrl();
            $authUrl = url()->path('/fitness/auth');
            if ($flushUrl == $authUrl) {
                $flushUrl = url()->path('/fitness');
            }
            $frame->session()->setFlush('url', $flushUrl);
            $this->skipNextWithRedirect(redirect($authUrl));
        }
    }
}
