<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/30/2015
 * Time: 6:13 AM
 */

return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        '/' => 'site/index',
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['user'],
            'pluralize'=>true,
        ],
        [
            'class' => 'yii\rest\UrlRule',
            'controller' => ['user-account'],
            'pluralize'=>false,
            'extraPatterns' => [
                'POST signup' => 'signup',
                'POST login' => 'login',
            ]
        ],
    ],
];