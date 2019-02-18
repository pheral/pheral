<?php

return [
    'connections' => [
        'main' => [
            'driver' => 'mysql',
            'host' => 'localhost',
            'base' => 'pheral',
            'user' => 'pheral',
            'pass' => 'pheral',
            'charset' => 'utf8',
            'table_prefix' => 'fitness',
        ],
    ],
    'default' => 'main',
];
