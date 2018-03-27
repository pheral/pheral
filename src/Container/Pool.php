<?php

namespace Pheral\Essential\Container;

class Pool
{
    protected static $instance;
    protected $aliases = [];
    protected $settings = [];
    protected $reflections = [];
    protected $concretes = [];

    /**
     * @return static
     */
    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    public static function get($alias)
    {
        return static::instance()->getClass($alias);
    }
    public static function make($alias, $abstract = null, $params = [], $singleton = false)
    {
        return static::instance()->makeClass($alias, $abstract, $params, $singleton);
    }
    public static function singleton($alias, $abstract = null, $params = [])
    {
        return static::instance()->makeSingleton($alias, $abstract, $params);
    }
    public function makeSingleton($alias, $abstract = null, $params = [])
    {
        return $this->makeClass($alias, $abstract, $params, true);
    }
    public function makeClass($alias, $abstract = null, $params = [], $singleton = false)
    {
        if ($concrete = $this->getClass($alias)) {
            return $concrete;
        }
        return $this->setClass($alias, $abstract, $params, $singleton);
    }
    public function getClass($alias)
    {
        if (is_object($alias)) {
            $alias = get_class($alias);
        }
        if ($settings = $this->getSettings($alias)) {
            $abstract = array_get($settings, 'abstract');
        } else {
            $abstract = $alias;
            $alias = $this->getAlias($abstract);
            $settings = $this->getSettings($alias);
        }
        if (!$settings) {
            return null;
        }
        if (array_get($settings, 'singleton')) {
            return $this->getConcrete($abstract);
        }
        $params = array_get($settings, 'params', []);
        return $this->newClass($abstract, $params);
    }
    protected function setClass($alias, $abstract = null, $params = [], $singleton = false)
    {
        if (!$abstract) {
            $abstract = $alias;
        }
        if (is_object($abstract)) {
            $object = $abstract;
            $abstract = get_class($abstract);
        }
        if ($abstract === static::class || is_subclass_of($abstract, static::class)) {
            skip('Pool can not contain itself', true);
        }
        $alias = $this->setAlias($abstract);
        $this->setSettings($alias, $abstract, $params, $singleton);
        $this->setReflection($abstract, $object ?? null);
        return $this->setConcrete($abstract, $object ?? null);
    }
    protected function newClass($abstract, $params = [])
    {
        $reflection = $this->getReflection($abstract);
        if ($reflection instanceof \ReflectionClass) {
            $constructor = $reflection->getConstructor();
            if ($constructor && $constructor->isPublic()) {
                $args = array_wrap($params, false);
                $concrete = $reflection->newInstanceArgs($args);
            } else {
                $concrete = $reflection->newInstanceWithoutConstructor();
            }
        }
        return $concrete ?? null;
    }
    protected function setAlias($abstract)
    {
        if (!$alias = $this->getAlias($abstract)) {
            $alias = string_end($abstract, '\\');
            if ($this->getSettings($alias)) {
                $alias = $abstract;
            }
            $this->aliases[$abstract] = $alias;
        }
        return $alias;
    }
    protected function getAlias($abstract)
    {
        return array_get($this->aliases, $abstract);
    }
    protected function setSettings($alias, $abstract, $params = [], $singleton = false)
    {
        if (!$settings = $this->getSettings($alias)) {
            $settings = [
                'abstract' => $abstract,
                'params' => array_wrap($params, false),
                'singleton' => $singleton ?? false,
            ];
            $this->settings[$alias] = $settings;
        }
        return $settings;
    }
    protected function getSettings($alias)
    {
        return array_get($this->settings, $alias);
    }
    protected function setReflection($abstract, $object = null)
    {
        if (!$reflection = $this->getReflection($abstract)) {
            if (is_object($object)) {
                $reflection = new \ReflectionObject($object);
            } else {
                $reflection = new \ReflectionClass($abstract);
            }
            $this->reflections[$abstract] = $reflection;
        }
        return $reflection;
    }

    protected function getReflection($abstract)
    {
        return array_get($this->reflections, $abstract);
    }
    protected function setConcrete($abstract, $object = null)
    {
        if (!$concrete = $this->getConcrete($abstract)) {
            $alias = $this->getAlias($abstract);
            $settings = $this->getSettings($alias);
            if (!is_object($object)) {
                $params = array_get($settings, 'params', []);
                $object = $this->newClass($abstract, $params);
            }
            $concrete = $object;
            if (array_get($settings, 'singleton')) {
                $this->concretes[$abstract] = $concrete;
            }
        }
        return $concrete;
    }
    protected function getConcrete($abstract)
    {
        return array_get($this->concretes, $abstract);
    }
}
