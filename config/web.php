<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'response' => [
            'class' => \app\components\ApiResponse::class,
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'cookieValidationKey' => 'maYaPr4VRXtCiSWn7vpLsttrHbKI3_Mn',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'class' => \app\components\ApiErrorHandler::class,
            'errorAction' => null,
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                // ───────── AUTH ─────────
                'POST auth/register'     => 'auth/register',
                'POST auth/login'        => 'auth/login',
                'POST auth/refresh'      => 'auth/refresh',
                'POST auth/logout'       => 'auth/logout',
                'POST auth/logout-all'   => 'auth/logout-all',
                'PATCH auth/password'    => 'auth/change-password',

                // ───────── SESSIONS ─────────
                'GET auth/sessions'                => 'auth/sessions',
                'DELETE auth/sessions/<id:\d+>'    => 'auth/logout-session',

                // ───────── PROFILE ─────────
                'GET profile'          => 'profile/view',
                'PATCH profile'        => 'profile/update',

                // ───────── POSTS ─────────
                'GET posts/my'         => 'post/my',
                'GET posts/<id:\d+>'   => 'post/view',
                'POST posts'           => 'post/create',
                'PATCH posts/<id:\d+>' => 'post/update',
                'DELETE posts/<id:\d+>' => 'post/delete',

                // ───────── COMMENTS ─────────
                'POST posts/<post_id:\d+>/comments' => 'comment/create',
                'GET  posts/<post_id:\d+>/comments' => 'comment/list',
                'PATCH comments/<comment_id:\d+>'   => 'comment/update',
                'DELETE comments/<comment_id:\d+>'  => 'comment/delete',

                // ───────── REACTIONS ─────────
                'POST <target_type:(post|comment)>/<target_id:\d+>/reaction' => 'reaction/toggle',
                'GET  <target_type:(post|comment)>/<target_id:\d+>/reaction' => 'reaction/index',

                // ───────── NOTIFICATIONS ─────────
                'GET notifications'                 => 'notification/index',
                'GET notifications/unread'    => 'notification/unread',
                'GET notifications/unread-count'    => 'notification/unread-count',
                'PATCH notifications/read'          => 'notification/mark-read',

                // ───────── FOLLOWS ─────────
                'POST users/<user_id:\d+>/follow'    => 'follow/follow',
                'DELETE users/<user_id:\d+>/follow'  => 'follow/unfollow',
                'GET users/<user_id:\d+>/follow'     => 'follow/is-following',
                'GET users/<user_id:\d+>/followers'  => 'follow/followers',
                'GET users/<user_id:\d+>/following'  => 'follow/following',
            ],

        ],
        'authManager' => [
            'class' => yii\rbac\DbManager::class,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
