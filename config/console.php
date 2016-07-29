<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'authManager' => [
            'class' => 'app\components\PhpManager',
            'defaultRoles' => ['user', 'manager', 'admin', 'master'],
            # if need to configure following files outside default folder (rbac)
//            'itemFile' => 'app\api\data\items.php', //Default path to items.php
//            'assignmentFile' => 'app\api\data\assignments.php', //Default path to assignments.php
//            'ruleFile' => 'app\api\data\rules.php', //Default path to rules.php
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
];
