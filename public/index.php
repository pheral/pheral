<?php

$path = dirname(realpath(__DIR__));

require $path . '/vendor/autoload.php';

$app = (new \Pheral\Essential\Application($path));

$app->run(new \Pheral\Essential\Core\Network());
