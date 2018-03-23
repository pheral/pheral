<?php
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
