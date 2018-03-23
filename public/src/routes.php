<?php

\Route::add('/home/{first}/{middle?}/{last?}', [
    'controller' => Home::class,
    'action' => 'about',
    'name' => 'home.about',
]);

\Route::add('/home', [
    'controller' => Home::class,
    'action' => 'index',
    'method' => 'GET',
    'name' => 'home.index',
]);

