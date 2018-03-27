<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Pheral\Essential\Application();
$app->run(new \Pheral\Essential\Core\Network());
