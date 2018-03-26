<?php

if (!function_exists('server')) {
    /**
     * @param string $key
     * @param null $default
     * @return \Pheral\Essential\Data\Server|mixed
     */
    function server($key = '', $default = null)
    {
        $server = \Pheral\Essential\Data\Server::instance();;
        return $key ? $server->get($key, $default) : $server;
    }
}

if (!function_exists('session')) {
    /**
     * @param string $key
     * @param null $default
     * @return \Pheral\Essential\Data\Session|mixed
     */
    function session($key = '', $default = null)
    {
        $session = \Pheral\Essential\Data\Session::instance();
        return $key ? $session->get($key, $default) : $session;
    }
}

if (!function_exists('cookies')) {
    /**
     * @param string $key
     * @param null $default
     * @return \Pheral\Essential\Data\Cookies|mixed
     */
    function cookies($key = '', $default = null)
    {
        $cookies = \Pheral\Essential\Data\Cookies::instance();
        return $key ? $cookies->get($key, $default) : $cookies;
    }
}

if (!function_exists('request')) {
    /**
     * @param string $key
     * @param null $default
     * @return \Pheral\Essential\Network\Request|mixed
     */
    function request($key = '', $default = null)
    {
        $request = \Pheral\Essential\Network\Request::instance();
        return $key ? $request->get($key, $default) : $request;
    }
}

if (!function_exists('response')) {
    /**
     * @param mixed $data
     * @return \Pheral\Essential\Network\Response
     */
    function response($data = null)
    {
        return \Pheral\Essential\Network\Response::make($data);
    }
}

if (!function_exists('redirect')) {
    /**
     * @param string $url
     * @param int $status
     * @return \Pheral\Essential\Network\Redirect
     */
    function redirect($url = '', $status = 302)
    {
        return \Pheral\Essential\Network\Redirect::make($url, $status);
    }
}

if (!function_exists('view')) {
    /**
     * @param string $path
     * @param array $data
     * @return \Pheral\Essential\Tools\View
     */
    function view($path = '', $data = [])
    {
        return \Pheral\Essential\Tools\View::make($path, $data);
    }
}
