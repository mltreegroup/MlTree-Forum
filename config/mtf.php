<?php
return [
    'checklist' => [
        'Index' => [
            'check' => ['upload'],
            'pass' => ['checkJWT', 'site', 'index', 'test'],
        ],
        'Comment' => [
            'check' => [],
            'pass' => ['index', 'read'],
            'auth' => [
                'create' => true,
                'update' => true,
                'delete' => 'admin',
            ],
        ],
        'Forum' => [
            'check' => [],
            'pass' => ['index', 'read'],
            'auth' => [
                'create' => true,
                'update' => true,
                'delete' => 'admin',
            ],
        ],
        'Topic' => [
            'check' => [],
            'pass' => ['index', 'read'],
            'auth' => [
                'create' => true,
                'update' => true,
                'delete' => 'admin',
                'up' => true,
                'down' => true,
            ],
        ],
        'User' => [
            'check' => [],
            'pass' => ['login', 'register', 'changpwd', 'forget', 'read'],
        ],
        'Admin' => [
            'check' => [],
            'pass' => [],
        ],
    ],
    'passOptionsRequest' => true,
];
