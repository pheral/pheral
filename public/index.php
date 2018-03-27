<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Pheral\Essential\Application(
    new \Pheral\Essential\Core\Network()
);

$app->run();
