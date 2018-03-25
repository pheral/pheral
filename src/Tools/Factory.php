<?php

namespace Pheral\Essential\Tools;

use Pheral\Essential\Data\Pool;

class Factory
{
    public static function make($alias, $abstract = null, $params = [], $singleton = false)
    {
        if ($instance = Pool::get($alias)) {
            return $instance;
        }
        if (!$abstract) {
            $abstract = $alias;
            $alias = is_string($abstract) ? string_end($abstract, '\\') : get_class($abstract);
        }
        if (is_string($abstract) && class_exists($abstract)) {
            try {
                $reflection = new \ReflectionClass($abstract);
            } catch (\ReflectionException $exception) {
                debug([
                    'DEBUG' => $exception->getMessage(),
                    'PLACE' => $exception->getFile() . ':' . $exception->getLine(),
                    'TRACE' => $exception->getTraceAsString()
                ]);
                return null;
            }
            $constructor = $reflection->getConstructor();
            if ($constructor && $constructor->isPublic()) {
                $args = array_wrap($params, false);
                $instance = $reflection->newInstanceArgs($args);
            } else {
                $instance = $reflection->newInstanceWithoutConstructor();
            }
        } else {
            $instance = $abstract;
        }
        if (!$singleton) {
            return $instance;
        }
        return Pool::set($alias, $instance);
    }
    public static function singleton($alias, $abstract = null, $params = [])
    {
        return self::make($alias, $abstract, $params, true);
    }
}
