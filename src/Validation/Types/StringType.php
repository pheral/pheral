<?php

namespace Pheral\Essential\Validation\Types;

use Pheral\Essential\Validation\Abstracts\TypeAbstract;

class StringType extends TypeAbstract
{
    public static function validate($value)
    {
        if (!is_string($value)) {
            return false;
        }
        return true;
    }
    public static function convert($value)
    {
        return (string)$value;
    }
}
