<?php

namespace Pheral\Essential\Tools;

use Pheral\Essential\Data\Server;

class View
{
    protected $path;
    protected $data;
    public function __construct($path, $data = [])
    {
        $segments = explode('.', trim($path, '.'));
        $folder = Server::instance()->path('/app/views');
        $absPath = $folder . '/' . implode('/', $segments) . '.php';
        if (!file_exists($absPath)) {
            debug('Template "' . $path . '" not found ');
        }
        $this->path = $absPath;
        $this->data = $data;
    }
    public function __toString()
    {
        $filePath = $this->getPath();
        if ($filePath) {
            if ($data = $this->getData()) {
                extract($data);
            }
            ob_start();
            include $filePath;
            return ob_get_clean();
        }
        return '';
    }

    public function render($data = [])
    {
        $this->setData($data);
        return $this->__toString();
    }
    public function setData($data = [])
    {
        if ($newData = array_wrap($data, false)) {
            $this->data = array_merge($this->getData(), $newData);
        }
        return array_wrap($this->data, false);
    }
    public function getData()
    {
        return array_wrap($this->data, false);
    }
    public function getPath()
    {
        return $this->path;
    }
}
