<?php

namespace App\Models;

use App\Entity\Test;
use App\Models\Abstracts\Model;

class Example extends Model
{
    public function getTest()
    {
        $query = $this->newQuery()
            ->fields(['id', 'title'])
            ->where('title', '=', 'second')
            ->orWhere('title', '=', 'first');
        $result = $query->select(Test::class);
        $data = $result->all();
        return $data;
    }
}
