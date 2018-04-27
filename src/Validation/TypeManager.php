<?php

namespace Pheral\Essential\Validation;

use Pheral\Essential\Storage\Config;
use Pheral\Essential\Validation\Interfaces\TypeInterface;
use Pheral\Essential\Validation\Types\BoolType;
use Pheral\Essential\Validation\Types\FloatType;
use Pheral\Essential\Validation\Types\IntType;
use Pheral\Essential\Validation\Types\StringType;

class TypeManager
{
    private static $instance;
    private $types = [];
    private $map = [
        'string' => StringType::class,
        'integer' => IntType::class,
        'float' => FloatType::class,
        'bool' => BoolType::class,
    ];
    private function __construct()
    {
        $customTypes = Config::instance()->get('validation.types', []);
        $this->map = array_merge($this->map, $customTypes);
    }
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * @param string $typeName
     * @return \Pheral\Essential\Validation\Interfaces\TypeInterface
     */
    public function get($typeName)
    {
        if ($type = ($this->types[$typeName] ?? null)) {
            return $type;
        }
        $className = $this->map[$typeName] ?? null;
        return $this->set($typeName, new $className);
    }

    /**
     * @param string $typeName
     * @param \Pheral\Essential\Validation\Interfaces\TypeInterface $type
     * @return \Pheral\Essential\Validation\Interfaces\TypeInterface
     */
    protected function set($typeName, TypeInterface $type)
    {
        return $this->types[$typeName] = $type;
    }
}
