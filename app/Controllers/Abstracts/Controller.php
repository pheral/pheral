<?php

namespace App\Controllers\Abstracts;

use Pheral\Essential\Layers\Controller as EssentialController;

abstract class Controller extends EssentialController
{
    protected $path = 'layouts.application';
}
