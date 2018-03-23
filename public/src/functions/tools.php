<?php
/**
 * @param string $key
 * @param null $default
 * @return \Server|mixed
 */
function server($key = '', $default = null)
{
    /**
     * @var \Server $server
     */
    $server = Storage::get('Server');
    return $key ? $server->get($key, $default) : $server;
}

/**
 * @param string $key
 * @param null $default
 * @return \Session|mixed
 */
function session($key = '', $default = null)
{
    /**
     * @var \Session $session
     */
    $session = Storage::get('Session');
    return $key ? $session->get($key, $default) : $session;
}

/**
 * @param string $key
 * @param null $default
 * @return \Cookies|mixed
 */
function cookies($key = '', $default = null)
{
    /**
     * @var \Cookies $cookies
     */
    $cookies = Storage::get('Cookies');
    return $key ? $cookies->get($key, $default) : $cookies;
}

/**
 * @param string $key
 * @param null $default
 * @return \Request|mixed
 */
function request($key = '', $default = null)
{
    /**
     * @var \Request $request
     */
    $request = Storage::get('Request');
    return $key ? $request->get($key, $default) : $request;
}

function response($data = null)
{
    return new Response($data);
}

function redirect($url = '', $status = 302)
{
    return new Redirect($url, $status);
}
