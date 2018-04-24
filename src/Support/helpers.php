<?php
function is_cli()
{
    return PHP_SAPI === 'cli';
}
/**
 * @SuppressWarnings(ExitExpression)
 */
function stop()
{
    exit;
}
/**
 * @SuppressWarnings(DevelopmentCodeFragment)
 * @param $args
 * @param null $from
 * @param bool $trace
 */
function inspect($args, $from = null, $trace = false, $html = true)
{
    $from = $from ?? inspect_from();
    if (!is_cli() && $html) {
        print '<pre style="border:1px solid red; background: #fffdf4;padding:5px;">';
    }

    array_map(function ($arg) {
        print var_export($arg, true) . PHP_EOL;
    }, $args);

    if (!is_cli() && $html) {
        print '<p style="font-size:10px;">';
    }
    print PHP_EOL . PHP_EOL . $from;
    if ($trace) {
        print PHP_EOL . PHP_EOL;
        debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    }
    if (!is_cli() && $html) {
        print '</p></pre>';
    }
}
function inspect_from()
{
    if ($backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)) {
        $call = next($backtrace);
    }
    return !empty($call) ? $call['file'] . ':' . $call['line'] : __FILE__.':'.__LINE__;
}
function debug(...$args)
{
    inspect($args, inspect_from());
}
function debug_stop(...$args)
{
    inspect($args, inspect_from(), true);
    stop();
}
function debug_raw(...$args)
{
    inspect($args, inspect_from(), true, false);
    stop();
}
function ignore($argument)
{
    return $argument;
}
function array_wrap($array, $force = false)
{
    return is_array($array) ? $array : ($array || $force ? [$array] : []);
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
function array_only($array, $keys)
{
    $only = [];
    $onlyKeys = array_wrap($keys);
    foreach ($onlyKeys as $key) {
        if (array_has($array, $key)) {
            $only[$key] = $array[$key];
        }
    }
    return $only;
}
function array_except($array, $keys)
{
    return array_expel($array, $keys);
}
function array_expel(&$array, $keys)
{
    $expelKeys = array_wrap($keys);
    foreach ($expelKeys as $key) {
        if (array_has($array, $key)) {
            unset($array[$key]);
        }
    }
    return $array;
}
function array_cut(&$array, $key, $default = null)
{
    $value = array_get($array, $key, $default);
    array_expel($array, $key);
    return $value;
}
function dot_array_get($array, $path, $default = null)
{
    $key = string_start_cut($path);
    if (!array_has($array, $key)) {
        return $default;
    }
    $value = array_get($array, $key);
    if ($path && is_array($value)) {
        return dot_array_get($value, $path, $default);
    } elseif ($path) {
        return $default;
    } else {
        return $value;
    }
}
function dot_array_set(&$array, $path, $value)
{
    if (!$path) {
        return ;
    }
    if (is_array($path)) {
        $path = implode('.', $path);
    }
    $key = string_start_cut($path);
    if (!array_has($array, $key)) {
        $nested = $path ? [] : $value;
    } else {
        $nested = array_get($array, $key, []);
        if (!is_array($nested)) {
            $nested = [];
        }
    }
    if ($path) {
        dot_array_set($nested, $path, $value);
    }
    $array[$key] = $path ? $nested : $value;
}
function dot_array_has($array, $path)
{
    if (!$path) {
        return false;
    }
    $key = string_start_cut($path);
    if (!array_has($array, $key)) {
        return false;
    }
    if ($path) {
        return dot_array_has(array_get($array, $key), $path);
    }
    return true;
}
function object_class($object)
{
    if (is_object($object)) {
        $class = get_class($object);
    } else {
        $class = $object;
    }
    return $class;
}
function object_name($object)
{
    return string_end(object_class($object), '\\');
}
function object_vars($object)
{
    return get_class_vars(object_class($object));
}
function string_segments($string, $delimiter = '.')
{
    return explode($delimiter, $string);
}
function string_start($string, $delimiter = '.')
{
    $segments = string_segments($string, $delimiter);
    return current($segments);
}
function string_start_cut(&$string, $delimiter = '.')
{
    $segments = string_segments($string, $delimiter);
    $segment = array_shift($segments);
    $string = implode($delimiter, $segments);
    return $segment;
}
function string_end($string, $delimiter = '.')
{
    $segments = string_segments($string, $delimiter);
    return end($segments);
}
function string_end_cut(&$string, $delimiter = '.')
{
    $segments = string_segments($string, $delimiter);
    $segment = array_pop($segments);
    $string = implode($delimiter, $segments);
    return $segment;
}
function string_wrap($string, $force = true)
{
    return is_string($string) ? $string : ($string || $force ? "{$string}" : "");
}
function string_camel_case($string, $ucFirst = false)
{
    $camel = '';
    $words = explode(' ', preg_replace('/[^a-z0-9]/si', ' ', $string));

    foreach ($words as $word) {
        if (!strlen($word)) {
            continue;
        }
        $camel .= ucfirst(strtolower($word));
    }
    if (!$ucFirst) {
        $camel = lcfirst($camel);
    }
    return $camel;
}
function string_snake_case($string)
{
    $snake = '';
    $origin = str_split($string);
    foreach ($origin as $index => $char) {
        if ($index && ctype_upper($char)) {
            $snake .= '_';
        }
        $snake .= strtolower($origin[$index]);
    }
    return $snake;
}
