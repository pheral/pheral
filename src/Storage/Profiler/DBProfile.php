<?php

namespace Pheral\Essential\Storage\Profiler;

use Pheral\Essential\Storage\Database\DB;

class DBProfile
{
    protected $history = [];
    public function all()
    {
        return $this->history;
    }
    public function get($connectName = '')
    {
        if (!$connectName) {
            $connectName = DB::connect()->connectName;
        }
        return array_get($this->history, $connectName, []);
    }
    public function push($sql, $connectName = '')
    {
        if (!$connectName) {
            $connectName = DB::connect()->connectName;
        }
        $connectHistory = array_get($this->history, $connectName, []);
        $connectHistory[] = $sql;
        $this->history[$connectName] = $connectHistory;
        return $this;
    }
    public function debug()
    {
        debug_trace($this->all());
    }
}