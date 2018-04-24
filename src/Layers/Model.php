<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Data\Base\Query;

abstract class Model
{
    public static function query($entity = '')
    {
        if ($entity instanceof Query) {
            return $entity;
        }
        $class = new static();
        if (!$class instanceof Model) {
            return null;
        }
        return $class->newQuery($entity);
    }

    public function newQuery($entity = '')
    {
        return new Query($entity);
    }
}
