<?php

$router = \Pheral\Essential\Network\Routing\Router::instance();

$router->add('/', [
    'controller' => \App\Controllers\Home::class,
    'action' => 'index',
    'method' => 'get'
]);

$router->add('/example/{param?}', [
    'controller' => \App\Controllers\Example::class,
    'action' => 'index',
    'method' => 'any'
]);
