<?php

namespace App\Models;

use App\Entity\Test;
use App\Models\Abstracts\Model;

class Example extends Model
{
    public function getTest()
    {
        return $this->newQuery(Test::class)
            ->select(['id', 'title'])
            ->where('title', '=', 'second')
            ->get();
    }
}
