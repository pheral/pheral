<?php

namespace Pheral\Essential\Validation\Types;

use Pheral\Essential\Validation\Abstracts\TypeAbstract;

class BoolType extends TypeAbstract
{
    public static function validate($value)
    {
        if (!is_bool($value) && !is_numeric($value)) {
            return false;
        }
        return true;
    }
    public static function convert($value)
    {
        return (bool)$value;
    }
}
