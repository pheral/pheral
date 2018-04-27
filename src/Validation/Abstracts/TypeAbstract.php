<?php

namespace Pheral\Essential\Validation\Abstracts;

use Pheral\Essential\Validation\Interfaces\TypeInterface;

abstract class TypeAbstract implements TypeInterface
{
    public static function extract($value)
    {
        return $value;
    }
}
