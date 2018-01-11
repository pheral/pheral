<?php

$preloadPath = __DIR__ . '/../preload';

require_once __DIR__ . '/../preload/autoload_vendor.php';

require __DIR__ . '/../preload/create_application.php';

$network = network(
    (new \Pheral\Essential\Network\Request())->make()
);

$response = $network->handle();

echo $response;