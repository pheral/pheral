<?php

namespace App\Models;

use App\Entity\Test;
use App\Models\Abstracts\Model;

class Example extends Model
{
    public function getTest()
    {
        $first = Test::query()
            ->where('title', '=', 'first')
            ->select()
            ->row();

        $query = $this->newQuery()
            ->fields(['t.id', 't.title', 'd.param'])
            ->table('test', 't')
            ->where('title', '=', 'second')
            ->orWhere('title', '=', 'third')
            ->leftJoin('dummy', 'd', 'd.test_id = t.id')
            ->limit(1)
            ->offset(1)
            ->orderBy('title', 'DESC')
            ->groupBy('id');

        if (isset($first->id)) {
            $query->whereNotIn('t.id', [$first->id]);
        }

        return $query->select()->all();
    }
}
