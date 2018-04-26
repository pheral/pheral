<?php

$router = \Pheral\Essential\Network\Routing\Router::instance();

$router->add('/{param?}', [
    'controller' => \App\Controllers\Help::class,
    'action' => 'index',
    'method' => 'any'
]);
