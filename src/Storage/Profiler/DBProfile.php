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
    public function get($connectionName = '')
    {
        if (!$connectionName) {
            $connectionName = DB::connection()->connectionName;
        }
        return array_get($this->history, $connectionName, []);
    }
    public function push($sql, $connectionName = '')
    {
        if (!$connectionName) {
            $connectionName = DB::connection()->connectionName;
        }
        $connectHistory = array_get($this->history, $connectionName, []);
        $connectHistory[] = $sql;
        $this->history[$connectionName] = $connectHistory;
        return $this;
    }
    public function debug()
    {
        debug_trace($this->all());
    }
}