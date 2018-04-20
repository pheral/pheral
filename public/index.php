<?php

$path = dirname(realpath(__DIR__));

require $path . '/vendor/autoload.php';

(new \Pheral\Essential\Application($path))
    ->run(new \Pheral\Essential\Core\Network());
