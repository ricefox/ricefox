<?php

$config = [
    'id' => 'admin',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'c-GrmlTWJMHsGUSXWX3VAENHEp3w2AH0',
        ],
        'devicedetect' => [
            'class' => 'alexandernst\devicedetect\DeviceDetect'
        ],
        'user' => [
            'identityClass' => 'ricefox\user\models\User',
            'enableAutoLogin' => true,
        ],
        'view'=>[
            'class'=>'ricefox\base\View'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error/error',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [

            ],
        ],
    ],
    'defaultRoute'=>'site/site/index',

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
