<?php

function debug_from($limit = 3)
{
    if ($backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit)) {
        $call = next($backtrace);
    }
    return !empty($call) ? $call['file'] . ':' . $call['line'] : __FILE__.':'.__LINE__;
}

/**
 * @param array ...$args
 * @SuppressWarnings(ExitExpression)
 */
function debug(...$args)
{
    print '<pre>';
    array_map(function ($arg) {
        print var_export($arg, true) . PHP_EOL;
    }, $args);
    print PHP_EOL . PHP_EOL . debug_from();
    exit;
}
