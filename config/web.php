<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'web',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
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
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'cookieValidationKey' => 'MXtBcX_ZOCJVA4g9MOz6JoHtUvNFgkv8',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
//            'enableStrictParsing' => true,
            'class' => 'yii\web\UrlManager',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //set this property to false to send mails to real email addresses
            //comment the following array to send mail using php's mail function
            'useFileTransport' => true,
            'viewPath' => '@app/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'tainvestor@gmail.com',
                'password' => 'Soe7014Ece',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'schedule' => [
            'class' => 'omnilight\scheduling\Schedule'
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
