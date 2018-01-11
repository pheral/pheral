<?php

require_once __DIR__ . '/../preload/loader.php';

$app = new Pheral\Essential\Basement\Application(__DIR__ . '/../');

$response = $app->handle(
    new \Pheral\Essential\Network\Request()
);