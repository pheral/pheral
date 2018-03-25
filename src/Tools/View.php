<?php

namespace Pheral\Essential\Tools;

class View
{
    protected $path;
    protected $data;
    public function __construct($path, $data = [])
    {
        $pathSegments = explode('.', trim($path, '.'));
        $dirViews = server()->path('/app/Views' );
        $absPath = $dirViews . '/' . implode('/', $pathSegments) . '.php';
        if (!file_exists($absPath)) {
            debug('Template "' . $path . '" not found ');
        }
        $this->path = $absPath;
        $this->data = $data;
    }
    public function render($data = [])
    {
        $filePath = $this->getPath();
        if ($data = array_merge($this->getData(), $data)) {
            extract($data);
        }
        ob_start();
        include $filePath;
        return ob_get_clean();
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
