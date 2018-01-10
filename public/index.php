<?php

require_once __DIR__ . '/../preload/loader.php';

$app = new Pheral\Essential\Application(__DIR__ . '/../');

$app->handle(
    new \Pheral\Essential\Network\Request()
);