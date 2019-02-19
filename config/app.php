<?php

return [
    'routes' => 'routes',
    'views' => 'renders/views',
    'wrappers' => [
        'network' => [
            \App\Wrappers\SessionUrls::class
        ],
    ]
];
