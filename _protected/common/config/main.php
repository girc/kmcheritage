<?php
return [
    'name' => (YII_ENV=='dev')?'KMC-Heritage (Development Version)':'KMC-Heritage',
    //'language' => 'sr',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'urlManager' =>require(__DIR__ . '/components/urlManager.php'),
        'urlManagerFrontEnd'=>require(__DIR__ . '/../../frontend/config/components/urlManager.php'),
        'urlManagerBackEnd'=>require(__DIR__ . '/../../backend/config/components/urlManager.php'),
        'urlManagerApi'=>require(__DIR__ . '/../../api/config/components/urlManager.php'),
        'assetManager' => [
            'bundles' => [
                // we will use bootstrap css from our theme
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [], // do not use yii default one
                ],

                // // use bootstrap js from CDN
                // 'yii\bootstrap\BootstrapPluginAsset' => [
                //     'sourcePath' => null,   // do not use file from our server
                //     'js' => [
                //         'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js']
                // ],
                // // use jquery from CDN
                // 'yii\web\JqueryAsset' => [
                //     'sourcePath' => null,   // do not use file from our server
                //     'js' => [
                //         '//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js',
                //     ]
                // ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/translations',
                    'sourceLanguage' => 'en',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/translations',
                    'sourceLanguage' => 'en'
                ],
            ],
        ],
    ], // components

    // set allias for our uploads folder so it can be shared by both frontend and backend applications
    // @root alias is defined in common/config/bootstrap.php file
    'aliases' => [
        '@uploadsPath' => '@uploadsPath',
        '@uploadsUrl' => '@uploadsUrl',
    ],
];
