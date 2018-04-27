<?php

namespace Pheral\Essential\Validation\Types;

use Pheral\Essential\Validation\Abstracts\TypeAbstract;

class IntType extends TypeAbstract
{
    public static function validate($value)
    {
        if (!is_numeric($value)) {
            return false;
        }
        return true;
    }
    public static function convert($value)
    {
        return (int)$value;
    }
}
