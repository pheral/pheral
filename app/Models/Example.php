<?php

namespace App\Models;

use App\Data\Dummy;
use App\Data\Test;
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
            ->fields(['t.id', 't.title'])
            ->table(Test::class, 't')
            ->leftJoin(Dummy::class, 'd', 'd.test_id = t.id')
            ->where('title', '=', 'second')
            ->orWhere('title', '=', 'third')
            ->whereNull('d.id')
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
