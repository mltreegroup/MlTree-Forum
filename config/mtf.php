<?php
return [
    'checklist' => [
        'Index' => [
            'check' => ['test', 'upload'],
            'pass' => ['checkJWT', 'site', 'index'],
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
