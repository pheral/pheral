<?php

namespace App\Wrappers;

use App\Wrappers\Abstracts\Wrapper;
use Pheral\Essential\Network\Frame;
use Pheral\Essential\Network\Output\Response;

class SessionUrls extends Wrapper
{
    public function terminate(Response $response = null)
    {
        $frame = Frame::instance();
        if (!$frame->isAjaxRequest() && $frame->isRequestMethod('GET')) {
            $frame->session()->setPreviousUrl($frame->getCurrentUrl());
        }
        if ($response && $response->hasRedirect()) {
            $frame->session()->setRedirectedUrl($response->redirect()->getUrl());
        }
    }
}
