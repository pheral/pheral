<?php

require_once __DIR__ . '/../preload/loader.php';

$app = new Pheral\Essential\Application(__DIR__ . '/../');

$app->handle();

$controller = new \App\Network\Controllers\HomeController();

$controller->index();