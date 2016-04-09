<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/31
 * Time: 19:31
 */

Yii::setAlias('@ricefox',dirname(__DIR__));
$config=[
    'basePath' => dirname(__DIR__),
    'language'=>'zh-CN',
    'sourceLanguage'=>'en-US',
    'components'=>[
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'ricefox\user\models\User',
            'enableAutoLogin' => true,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,
            'transport'=>[
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => 'tiehuoban',
                'password' => '3g7529021021',
                'port' => '25',
                'encryption' => 'tls',
            ]
        ],
        'db' => require(__DIR__ . '/db.php'),
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
        'setting'=>[
            'class'=>'ricefox\setting\Setting'
        ],

    ],
    'modules'=>[
        'user'=>[
            'class'=>'ricefox\user\UserModule'
        ],
        'article' => [
            'class' => 'ricefox\article\ArticleModule',
        ],
        'setting' => [
            'class' => 'ricefox\setting\SettingModule',
        ],
        'block'=>[
            'class'=>'ricefox\block\BlockModule',
        ],
        'job' => [
            'class' => 'ricefox\job\JobModule',
        ],
        'site'=>[
            'class' => 'ricefox\site\SiteModule',
        ]

    ]
];
return $config;