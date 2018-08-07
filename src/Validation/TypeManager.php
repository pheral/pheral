<?php

namespace Pheral\Essential\Validation;

use Pheral\Essential\Exceptions\NetworkException;
use Pheral\Essential\Storage\Config;
use Pheral\Essential\Validation\Types\BoolType;
use Pheral\Essential\Validation\Types\FloatType;
use Pheral\Essential\Validation\Types\IntType;
use Pheral\Essential\Validation\Types\StringType;
use Pheral\Essential\Validation\Types\TimeType;
use Pheral\Essential\Validation\Types\DateType;
use Pheral\Essential\Validation\Types\DateTimeType;

class TypeManager
{
    protected static $init = false;
    protected static $types = [
        'string' => StringType::class,
        'integer' => IntType::class,
        'float' => FloatType::class,
        'bool' => BoolType::class,
        'time' => TimeType::class,
        'date' => DateType::class,
        'datetime' => DateTimeType::class,
    ];
    protected static function init()
    {
        $customTypes = Config::instance()->get('validation.types', []);
        self::$types = array_merge(self::$types, $customTypes);
        return true;
    }
    /**
     * @param string $typeName
     * @return \Pheral\Essential\Validation\Interfaces\TypeInterface
     */
    public static function get($typeName)
    {
        if (!self::$init) {
            self::$init = self::init();
        }
        if ($type = self::$types[$typeName] ?? false) {
            return $type;
        }
        throw new NetworkException(500, 'Invalid type');
    }
    public static function validate($typeName, $value)
    {
        return self::get($typeName)::validate($value);
    }
    public static function convert($typeName, $value)
    {
        return self::get($typeName)::convert($value);
    }
    public static function extract($typeName, $value)
    {
        return self::get($typeName)::extract($value);
    }
}
