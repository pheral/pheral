<?php

namespace Pheral\Essential\Validation\Types;

use Pheral\Essential\Validation\Abstracts\TypeAbstract;

class DateTimeType extends TypeAbstract
{
    public static function validate($value)
    {
        if (is_null($value)) {
            return true;
        } elseif (!is_string($value)) {
            return false;
        } elseif (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value) === false) {
            return false;
        }
        return true;
    }
    public static function convert($value)
    {
        if (is_null($value)) {
            return $value;
        } elseif ($value == '0000-00-00 00:00:00') {
            return null;
        }
        return (string)$value;
    }
}
