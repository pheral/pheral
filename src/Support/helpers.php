<?php

/**
 * @SuppressWarnings(ExitExpression)
 */
function stop()
{
    exit;
}

/**
 * @param array ...$args
 */
function debug(...$args)
{
    print '<pre>';
    array_map(function ($arg) {
        print var_export($arg, true) . PHP_EOL;
    }, $args);
    print PHP_EOL . PHP_EOL . debug_from();
    stop();
}

function debug_from($limit = 3)
{
    if ($backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit)) {
        $call = next($backtrace);
    }
    return !empty($call) ? $call['file'] . ':' . $call['line'] : __FILE__.':'.__LINE__;
}

/**
 * @param $argument
 * @return mixed
 */
function ignore($argument)
{
    return $argument;
}
function array_set(&$array, $key, $value)
{
    if (is_array($array)) {
        $array[$key] = $value;
    }
    return $array;
}
function array_has($array, $key)
{
    return is_array($array) && array_key_exists($key, $array);
}
function array_get($array, $key, $default = null)
{
    return array_has($array, $key) ? $array[$key] : $default;
}
function array_drop(&$array, $key)
{
    if (array_has($array, $key)) {
        unset($array[$key]);
    }
    return $array;
}
function array_cut(&$array, $key, $default = null)
{
    $value = array_get($array, $key, $default);
    array_drop($array, $key);
    return $value;
}
function array_wrap($array, $force = true)
{
    return is_array($array) ? $array : ($array || $force ? [$array] : []);
}
function string_start($string, $delimiter = '.')
{
    $contents = explode($delimiter, $string);
    return current($contents);
}
function string_end($string, $delimiter = '.')
{
    $contents = explode($delimiter, $string);
    return end($contents);
}
function string_wrap($string, $force = true)
{
    return is_string($string) ? $string : ($string || $force ? "{$string}" : "");
}
