<?php

namespace Pheral\Essential\Network;

use Pheral\Essential\Tools\View;

class Response
{
    protected $redirect;
    protected $content = '';
    public function __construct($data = null)
    {
        if ($data instanceof Redirect) {
            $this->setRedirect($data);
        } elseif (isset($data)) {
            $this->setContent($data);
        }
    }
    public static function make($data = [])
    {
        return new static($data);
    }
    public function setContent($data)
    {
        if (!isset($data)) {
            return $this;
        }
        if ($data instanceof View) {
            $content = $data->render();
        } elseif (!is_string($data)) {
            $content = json_encode($data);
        } else {
            $content = $data;
        }
        $this->content = $content;
        return $this;
    }
    public function hasContent()
    {
        return strlen($this->content) > 0;
    }
    public function send()
    {
        echo $this->content;
    }
    public function setRedirect($target = '', $status = 302)
    {
        if ($target instanceof Redirect) {
            $this->redirect = $target;
        } else {
            $this->redirect = new Redirect($target, $status);
        }
        return $this;
    }
    public function hasRedirect(): bool
    {
        return $this->redirect instanceof Redirect;
    }
    /**
     * @return \Pheral\Essential\Network\Redirect|null
     */
    public function redirect()
    {
        return $this->redirect;
    }
}
