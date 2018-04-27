<?php

namespace Pheral\Essential\Validation\Interfaces;

interface TypeInterface
{
    public static function validate($value);
    public static function convert($value);
    public static function extract($value);
}
