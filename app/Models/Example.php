<?php

namespace App\Models;

use App\DataTables\Dummy;
use App\DataTables\Test;
use App\Models\Abstracts\Model;

class Example extends Model
{
    public function addTest($title = null)
    {
        return Test::query()
            ->values(['title' => $title ?? microtime()])
            ->insert()
            ->lastInsertId();
    }
    public function getTest()
    {
        $first = Test::query()
            ->where('title', '=', 'first')
            ->select()
            ->row();

        $query = $this->newQuery()
            ->fields(['t.id', 't.title', 'd.param'])
            ->table(Test::class, 't')
            ->leftJoin(Dummy::class, 'd', 'd.test_id = t.id')
            ->where('t.title', '=', 'second')
            ->orWhere('t.title', '!=', 'first')
            ->whereNull('d.id')
            ->limit(1)
            ->offset(1)
            ->orderBy('t.title', 'DESC')
            ->groupBy('t.id');

        if (isset($first->id)) {
            $query->whereNotIn('t.id', [$first->id]);
        }

        $result = $query->select();

        return [
            'RESULT 1' => $first,
            'RESULT 2' => $result->all()
        ];
    }
}
