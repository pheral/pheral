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

$router->wrap([
    \App\Wrappers\Fitness\Authorized::class,
], function () use ($router) {

    $router->add('/fitness', [
        'controller' => \App\Controllers\Fitness::class,
        'action' => 'index',
    ]);

});
