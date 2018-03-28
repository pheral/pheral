<?php

require __DIR__ . '/../vendor/autoload.php';

$application = (new \Pheral\Essential\Application());
$application->run(new \Pheral\Essential\Core\Network());
