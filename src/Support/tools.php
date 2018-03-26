<?php

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

/**
 * @param mixed $data
 * @return \Pheral\Essential\Network\Response
 */
function response($data = null)
{
    return new \Pheral\Essential\Network\Response($data);
}

/**
 * @param string $url
 * @param int $status
 * @return \Pheral\Essential\Network\Redirect
 */
function redirect($url = '', $status = 302)
{
    return new \Pheral\Essential\Network\Redirect($url, $status);
}
