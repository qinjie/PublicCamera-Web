<?php
return [
    'user' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'view',
            'index',
            'create',
        ],
    ],
    'manager' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'user',
            'update',
            'delete',
        ],
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'manager',
        ],
    ],
    'master' => [
        'type' => 1,
        'ruleName' => 'userRole',
        'children' => [
            'admin',
        ],
    ],
    'create' => [
        'type' => 2,
        'description' => 'create',
    ],
    'index' => [
        'type' => 2,
        'description' => 'index',
    ],
    'update' => [
        'type' => 2,
        'description' => 'update',
    ],
    'delete' => [
        'type' => 2,
        'description' => 'delete',
    ],
    'view' => [
        'type' => 2,
        'description' => 'view',
    ],
];
