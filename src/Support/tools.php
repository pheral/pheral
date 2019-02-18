<?php

if (!function_exists('app')) {
    /**
     * @return \Pheral\Essential\Application
     */
    function app()
    {
        return \Pheral\Essential\Application::instance();
    }
}

if (!function_exists('config')) {
    /**
     * @param string $key
     * @param null $default
     * @return \Pheral\Essential\Storage\Config|mixed
     */
    function config($key = '', $default = null)
    {
        return app()->config($key, $default);
    }
}

if (!function_exists('frame')) {
    /**
     * @return \Pheral\Essential\Network\Frame
     */
    function frame()
    {
        return \Pheral\Essential\Network\Frame::instance();
    }
}

if (!function_exists('server')) {
    /**
     * @param string $key
     * @param null $default
     * @return \Pheral\Essential\Storage\Server|mixed
     */
    function server($key = '', $default = null)
    {
        $server = \Pheral\Essential\Storage\Server::instance();
        return $key ? $server->get($key, $default) : $server;
    }
}

if (!function_exists('session')) {
    /**
     * @param string $key
     * @param null $default
     * @return \Pheral\Essential\Storage\Session|mixed
     */
    function session($key = '', $default = null)
    {
        $session = \Pheral\Essential\Storage\Session::instance();
        return $key ? $session->get($key, $default) : $session;
    }
}

if (!function_exists('cookies')) {
    /**
     * @param string $key
     * @param null $default
     * @return \Pheral\Essential\Storage\Cookies|mixed
     */
    function cookies($key = '', $default = null)
    {
        $cookies = \Pheral\Essential\Storage\Cookies::instance();
        return $key ? $cookies->get($key, $default) : $cookies;
    }
}

if (!function_exists('request')) {
    /**
     * @param string $key
     * @param null $default
     * @return \Pheral\Essential\Storage\Request|mixed
     */
    function request($key = '', $default = null)
    {
        $request = \Pheral\Essential\Storage\Request::instance();
        return $key ? $request->get($key, $default) : $request;
    }
}

if (!function_exists('response')) {
    /**
     * @param mixed $data
     * @return \Pheral\Essential\Network\Output\Response
     */
    function response($data = null)
    {
        return \Pheral\Essential\Network\Output\Response::make($data);
    }
}

if (!function_exists('redirect')) {
    /**
     * @param string $url
     * @param int $status
     * @return \Pheral\Essential\Network\Output\Redirect
     */
    function redirect($url = '', $status = 302)
    {
        return \Pheral\Essential\Network\Output\Redirect::make($url, $status);
    }
}

if (!function_exists('view')) {
    /**
     * @param string $path
     * @param array $data
     * @return \Pheral\Essential\Layers\View
     */
    function view($path = '', $data = [])
    {
        return \Pheral\Essential\Layers\View::make($path, $data);
    }
}
if (!function_exists('profiler')) {
    /**
     * @return \Pheral\Essential\Storage\Profiler
     */
    function profiler()
    {
        return \Pheral\Essential\Storage\Profiler::instance();
    }
}

if (!function_exists('error')) {
    /**
     * @param $message
     * @param $code
     * @throws \Pheral\Essential\Exceptions\NetworkException
     */
    function error($message, $code = 500)
    {
        app()->error($message, $code);
    }
}

if (!function_exists('error404')) {
    /**
     * @param $message
     * @throws \Pheral\Essential\Exceptions\NetworkException
     */
    function error404($message = '')
    {
        app()->error($message, 404);
    }
}
