<?php

namespace Pheral\Essential\Exceptions;

use Pheral\Essential\Layers\View;
use Pheral\Essential\Network\Output\Response;

class ExceptionHandler
{
    public function display(\Throwable $exception)
    {
        if ($exception instanceof NetworkException) {
            $httpCode = $exception->getHttpCode();
            $view = View::make('errors.'.$httpCode);
            if ($view->exists()) {
                $view->setData([
                    'message' => $exception->getMessage(),
                    'code' => $httpCode,
                    'trace' => $exception->getTraceAsString(),
                ]);
                Response::make($view)->send();
                stop();
            }
        }
        debug([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
