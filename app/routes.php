<?php

use \Pheral\Essential\Network\Routing\Router;

$router = Router::instance();

$router->add('/help/{first}/{middle?}/{last?}', [
    'controller' => \App\Controllers\Help::class,
    'action' => 'about',
    'method' => 'GET',
    'name' => 'help.index',
]);

$router->add('/help', [
    'controller' => \App\Controllers\Help::class,
    'action' => 'index',
    'method' => 'GET',
    'name' => 'help.index',
]);

