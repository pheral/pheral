<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Network\Output\Redirect;
use Pheral\Essential\Network\Output\Response;

abstract class Wrapper
{
    protected $callNext;

    public function __construct(callable $callNext)
    {
        $this->callNext = $callNext;
    }

    public function handle()
    {
        $this->beforeController();

        $callNext = $this->callNext;
        if (is_callable($callNext)) {
            $response = $callNext();
        } else {
            $response = $callNext;
        }

        $this->afterController();

        return $response;
    }

    /**
     * Actions BEFORE controller generated response
     */
    public function beforeController()
    {
        //debug(static::class.'::'.__FUNCTION__.'()');
    }

    /**
     * Actions AFTER controller generated response
     */
    public function afterController()
    {
        //debug(static::class.'::'.__FUNCTION__.'()');
    }

    /**
     * Actions AFTER application send response
     *
     * @param \Pheral\Essential\Network\Output\Response $response
     */
    public function terminate(Response $response = null)
    {
        //debug(static::class.'::'.__FUNCTION__.'()');
        ignore($response);
    }

    protected function skipNext()
    {
        $this->callNext = null;
    }

    protected function skipNextWithMessage(string $message)
    {
        $this->callNext = $message;
    }

    protected function skipNextWithRedirect(Redirect $redirect)
    {
        $this->callNext = $redirect;
    }
}
