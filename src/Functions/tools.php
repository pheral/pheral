<?php
/**
 * @param string $key
 * @param null $default
 * @return \Pheral\Essential\Data\Server|mixed
 */
function server($key = '', $default = null)
{
    /**
     * @var \Pheral\Essential\Data\Server $server
     */
    $server = \Pheral\Essential\Data\Pool::get('Server');
    return $key ? $server->get($key, $default) : $server;
}

/**
 * @param string $key
 * @param null $default
 * @return \Pheral\Essential\Data\Session|mixed
 */
function session($key = '', $default = null)
{
    /**
     * @var \Pheral\Essential\Data\Session $session
     */
    $session = \Pheral\Essential\Data\Pool::get('Session');
    return $key ? $session->get($key, $default) : $session;
}

/**
 * @param string $key
 * @param null $default
 * @return \Pheral\Essential\Data\Cookies|mixed
 */
function cookies($key = '', $default = null)
{
    /**
     * @var \Pheral\Essential\Data\Cookies $cookies
     */
    $cookies = \Pheral\Essential\Data\Pool::get('Cookies');
    return $key ? $cookies->get($key, $default) : $cookies;
}

/**
 * @param string $key
 * @param null $default
 * @return \Pheral\Essential\Network\Request|mixed
 */
function request($key = '', $default = null)
{
    /**
     * @var \Pheral\Essential\Network\Request $request
     */
    $request = \Pheral\Essential\Data\Pool::get('Request');
    return $key ? $request->get($key, $default) : $request;
}

function response($data = null)
{
    return new \Pheral\Essential\Network\Response($data);
}

function redirect($url = '', $status = 302)
{
    return new \Pheral\Essential\Network\Redirect($url, $status);
}
