<?php

return [
    // 'defaults' => [
    //     'guard' => 'api',
    //     'passwords' => 'users',
    // ],

    'guards' => [
        // 'api' => [
        //     'driver' => 'jwt',
        //     'provider' => 'users',
        // ],
        'admins' => [
            'driver' => 'jwt',
            'provider' => 'admins'
        ],
        'users' => [
            'driver' => 'jwt',
            'provider' => 'users'
        ]
    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Admin::class
        ],
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class
        ]
    ]
];