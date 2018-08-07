<?php

namespace App\Models;

use App\DataTables\Reports\Tasks;
use App\Models\Abstracts\Model;

class Reports extends Model
{
    public function getTasks()
    {
        return Tasks::query()->select()->all();
    }
}
