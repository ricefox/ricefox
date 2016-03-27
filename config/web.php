<?php

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language'=>'zh-CN',
    'sourceLanguage'=>'en-US',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'c-GrmlTWJMHsGUSXWX3VAENHEp3w2AH0',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'ricefox\user\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
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
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'i18n'=>[
            'translations' => [
                'rf*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@ricefox/messages',
                    //'sourceLanguage' => 'en-US',
                    //'fileMap' => [
                    //    'app' => 'app.php',
                    //    'app/error' => 'error.php',
                    //],
                ],
            ],
        ],
        'authManager'=>[
            'class'=>'yii\rbac\DbManager',
            'itemTable'=>'{{%user_auth_item}}',
            'itemChildTable'=>'{{%user_auth_item_child}}',
            'assignmentTable'=>'{{%user_auth_assignment}}',
            'ruleTable'=>'{{%user_auth_rule}}'
        ],
    ],
    'modules'=>[
        'user'=>[
            'class'=>'ricefox\user\UserModule'
        ],
        'article' => [
            'class' => 'ricefox\article\ArticleModule',
        ],
    ],
    'params' => include(__DIR__.'/params.php'),
];
if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.174.1'], // adjust this to your needs
        'generators' => [ //here
            'module' => [ // generator name
                'class' => 'yii\gii\generators\module\Generator', // generator class
                'templates' => [ //setting for out templates
                    'm1' => '@ricefox/gii/module/default', // template name => path to template
                ]
            ],
            'crud'=>[
                'class' => 'ricefox\gii\crud\Generator', // generator class
                'templates' => [ //setting for out templates
                    'has-tabs' => '@ricefox/gii/crud/has-tabs', // template name => path to template
                    'admin-has-tabs' => '@ricefox/gii/crud/admin-has-tabs', // template name => path to template
                ]
            ],
            'model' => [ // generator name
                'class' => 'ricefox\gii\model\Generator', // generator class
                'templates' => [ //setting for out templates
                    'm1' => '@ricefox/gii/model/m1', // template name => path to template
                ]
            ],
        ],
    ];
}
return $config;
