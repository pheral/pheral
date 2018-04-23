<?php

namespace App\Models;

use Pheral\Essential\Data\Base\DB;

class Test
{
    public function get()
    {
        return DB::connect()
            ->query('SELECT id, title FROM test')
            ->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
}
