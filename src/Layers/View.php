<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Data\Server;
use Pheral\Essential\Exceptions\NetworkException;

class View
{
    protected $name;
    protected $path;
    protected $data;
    public function __construct($path = '', $data = [])
    {
        if ($path) {
            $this->setPath($path);
        }
        if ($data) {
            $this->setData($data);
        }
    }
    public function __toString()
    {
        return $this->render();
    }
    public static function make($path = '', $data = [])
    {
        return new static($path, $data);
    }
    public function render($data = [])
    {
        $this->setData($data);
        if ($filePath = $this->getPath()) {
            if ($vars = $this->getData()) {
                extract($vars);
            }
            ob_start();
            include $filePath;
            return ob_get_clean();
        } else {
            throw new NetworkException(500, 'Template "' . $this->name . '" not found ');
        }
    }
    public function setData($data = [])
    {
        if ($newData = array_wrap($data, false)) {
            $this->data = array_merge($this->getData(), $newData);
        }
        return $this;
    }
    public function getData()
    {
        return array_wrap($this->data, false);
    }
    public function setPath($path)
    {
        if ($path instanceof View) {
            $view = $path;
            if ($name = $view->getName()) {
                $this->name = $name;
            }
            if ($path = $view->getPath()) {
                $this->path = $path;
            }
            if ($data = $view->getData()) {
                $this->setData($data);
            }
        } else {
            $this->name = $path;
            if ($absPath = static::exists($path)) {
                $this->path = $absPath;
            }
        }
        return $this;
    }
    public function getPath()
    {
        return $this->path;
    }
    public function getName()
    {
        return $this->name;
    }
    public function exists($path = '')
    {
        if ($path) {
            $segments = explode('.', trim($path, '.'));
            $folder = Server::instance()->path('/app/views');
            $absPath = $folder . '/' . implode('/', $segments) . '.php';
        } else {
            $absPath = $this->path;
        }
        return $absPath && file_exists($absPath) ? $absPath : false;
    }
}
