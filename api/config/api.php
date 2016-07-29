<?php

$db = require(__DIR__ . '/../../config/db.php');
$params = array_merge(
    require(__DIR__ . '/../../config/params.php'),
    require(__DIR__ . '/params.php')
);

$config = [
    'id' => 'api',
    'name' => 'Public Camera',
    // Need to get one level up:
    'basePath' => dirname(__DIR__) . '/..',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'app\api\modules\v1\Module',
        ],
    ],
    'timeZone' => 'Asia/Singapore',
    'components' => [
        'authManager' => [
            'class' => 'app\components\PhpManager',
            'defaultRoles' => ['user', 'manager', 'admin', 'master'],
            # if need to configure following files outside default folder (rbac)
//            'itemFile' => 'app\api\data\items.php', //Default path to items.php
//            'assignmentFile' => 'app\api\data\assignments.php', //Default path to assignments.php
//            'ruleFile' => 'app\api\data\rules.php', //Default path to rules.php
        ],
        'request' => [
            // Enable JSON Input
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'cookieValidationKey' => 'MXtBcX_ZOCJVA4g9MOz6JoHtUvNFgkv8',
        ],
        'response' => [
            'format' => 'json',
//            'class' => 'yii\web\Response',
//            'on beforeSend' => function ($event) {
//                $response = $event->sender;
//                if ($response->data !== null) {
//                    $response->data = [
//                        'success' => $response->isSuccessful,
//                        'data' => $response->data,
//                    ];
//                    $response->statusCode = 200;
//                }
//            },
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logFile' => '@app/runtime/logs/api-error.log'
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['warning'],
                    'logFile' => '@app/runtime/logs/api-warning.log'
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'logFile' => '@app/runtime/logs/api-info.log'
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
//                # API for Account
//                'GET <version:\w+>/account/login' => '<version>/account/login',
//                'GET <version:\w+>/account/logout-all-sessions' => '<version>/account/logout-all-sessions',
//                'GET <version:\w+>/account/logout-current-session' => '<version>/account/logout-current-session',
                # API for ActiveRecords
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/node-file',
                    'extraPatterns' => [
                        'GET search' => 'search',
                        'POST upload' => 'upload',
                        'GET latest-by-project/{projectId}' => 'latest-by-project',
                        'GET book-one' => 'book-one',
                        'GET latest-by-project-and-label/{projectId}/{label}' => 'latest-by-project-and-label',
                        'GET latest-by-project-and-type/{projectId}/{type}' => 'latest-by-project-and-type',
                        'DELETE delete-hours-older/{hours}' => 'delete-hours-older',
                        'DELETE keep-latest-n-each/{cnt}' => 'keep-latest-n-each',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                        '{projectId}' => '<projectId:\\w+>',
                        '{label}' => '<label:[\\w+\\s+]+>',
                        '{hours}' => '<hours:\\d+>',
                        '{cnt}' => '<cnt:\\d+>',],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/node-data',
                    'extraPatterns' => [
                        'GET search' => 'search',
                        'GET latest-by-project/{projectId}' => 'latest-by-project',
                        'GET latest-by-project-and-label/{projectId}/{label}' => 'latest-by-project-and-label',
                        'GET latest-by-project-and-type/{projectId}/{type}' => 'latest-by-project-and-type',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                        '{projectId}' => '<projectId:\\w+>',
                        '{label}' => '<label:[\\w+\\s+]+>',],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/node-setting',
                    'extraPatterns' => [
                        'GET search' => 'search',
                        'PUT update-ip/{nodeId}' => 'update-ip'],
                    'tokens' => [
                        # Keep 'id' for default CRUD action
                        '{id}' => '<id:\\w+>',
                        # for update-ip action
                        '{nodeId}' => '<nodeId:\\w+>',],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/node-summary',
                    'extraPatterns' => [
                        'GET search' => 'search',
                        'GET latest-by-project/{projectId}' => 'latest-by-project',
                        'POST node-crowd-average' => 'node-crowd-average'],
                    'tokens' => [
                        # Keep 'id' for default CRUD action
                        '{id}' => '<id:\\w+>',
                        '{projectId}' => '<projectId:\\w+>',],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/floor-data',
                    'extraPatterns' => [
                        'GET search' => 'search',
                        'GET list-by-project-and-type/{projectId}/{type}' => 'list-by-project-and-type',
                        'GET list-by-floor-and-type/{floorId}/{type}' => 'list-by-floor-and-type',
                        'GET latest-by-project-and-type/{projectId}/{type}' => 'latest-by-project-and-type',
                        'GET latest-by-floor-and-type/{floorId}/{type}' => 'latest-by-floor-and-type',
                        'GET list-by-project-and-label/{projectId}/{label}' => 'list-by-project-and-label',
                        'GET list-by-floor-and-label/{floorId}/{label}' => 'list-by-floor-and-label',
                        'GET latest-by-project-and-label/{projectId}/{label}' => 'latest-by-project-and-label',
                        'GET latest-by-floor-and-label/{floorId}/{label}' => 'latest-by-floor-and-label',
                        'POST floor-crowd-today' => 'floor-crowd-today',
                        'POST floor-crowd-weekly' => 'floor-crowd-weekly',
                        'POST floor-crowd-monthly' => 'floor-crowd-monthly',
//                        'POST floor-crowd-weekdays' => 'floor-crowd-weekdays',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                        '{projectId}' => '<projectId:\\w+>',
                        '{floorId}' => '<floorId:\\w+>',
                        '{label}' => '<label:[\\w+\\s+]+>',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/project', 'v1/floor', 'v1/floor-setting', 'v1/node', 'v1/project-user'],
                    'extraPatterns' => ['GET search' => 'search'],
                ],
                # For Testing Purpose
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/country',
                    'extraPatterns' => [
                        'GET say-hello' => 'say-hello',
                        'GET search' => 'search',
                    ],
                    'except' => [],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            # Settings for Restful API
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'db' => $db,
    ],
    'params' => $params,
];

return $config;

