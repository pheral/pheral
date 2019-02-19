<?php

return [
    'routes' => 'routes',
    'views' => 'resources/views',
    'wrappers' => [
        'network' => [
            \Pheral\Essential\Network\Wrappers\SessionUrls::class
        ],
    ]
];
