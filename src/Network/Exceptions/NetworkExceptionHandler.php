<?php

namespace Pheral\Essential\Network\Exceptions;

use Pheral\Essential\Layers\View;
use Pheral\Essential\Network\Output\Response;

class NetworkExceptionHandler
{
    public function display(\Throwable $exception)
    {
        if ($exception instanceof NetworkException) {
            $httpCode = $exception->getHttpCode();
            $view = View::make('errors.'.$httpCode, [
                'message' => $exception->getMessage(),
                'code' => $httpCode,
                'trace' => $exception->getTraceAsString(),
            ]);
            Response::make($view)->send();
        } else {
            debug([
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'trace' => $exception->getTraceAsString()
            ]);
        }
        stop();
    }
}
