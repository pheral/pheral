<?php

$router = \Pheral\Essential\Network\Routing\Router::instance();

$router->add('/help/{first}/{middle?}/{last?}', [
    'controller' => \App\Controllers\Help::class,
    'action' => 'about',
    'method' => 'GET',
    'name' => 'help.index',
]);

$router->add('/help', [
    'controller' => \App\Controllers\Help::class,
    'action' => 'index',
    'name' => 'help.index',
]);

