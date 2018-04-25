<?php

$path = dirname(realpath(__DIR__));

require $path . '/vendor/autoload.php';

$app = \Pheral\Essential\Application::instance($path);

$app->run(new \Pheral\Essential\Core\Network());
