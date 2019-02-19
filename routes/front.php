<?php

$router = \Pheral\Essential\Network\Routing\Router::instance();

$router->add('/', [
    'controller' => \App\Controllers\HomeController::class,
    'action' => 'index',
    'method' => 'get'
]);

$router->add('/example/{param?}', [
    'controller' => \App\Controllers\ExampleController::class,
    'action' => 'index',
    'method' => 'any'
]);

$router->add('/fitness/auth', [
    'controller' => \App\Controllers\Fitness\AuthController::class,
    'action' => 'index',
    'method' => 'get'
]);

$router->add('/fitness/auth/login', [
    'controller' => \App\Controllers\Fitness\AuthController::class,
    'action' => 'login',
    'method' => 'post'
]);

$router->add('/fitness/auth/logout', [
    'controller' => \App\Controllers\Fitness\AuthController::class,
    'action' => 'logout',
    'method' => 'get'
]);

$router->wrap([
    \App\Wrappers\Fitness\Authorized::class,
], function () use ($router) {

    $router->add('/fitness', [
        'controller' => \App\Controllers\Fitness\HomeController::class,
        'action' => 'index',
    ]);

});
