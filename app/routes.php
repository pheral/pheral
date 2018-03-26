<?php

$router = \Pheral\Essential\Network\Routing\Router::instance();

$router->add('/{page?}', [
    'controller' => \App\Controllers\Help::class,
    'action' => 'index',
]);

