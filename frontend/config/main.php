<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'homeUrl'=>'/',
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'layout'=>'stroy',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl'=>''
        ],  
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'stroy-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
            'maxSourceLines' => 20,
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'reports/report/clientinvoicereportxls/<id:\d+>' => 'reports/report/clientinvoicereportxls',
                'reports/report/clientreportxls/<id:\d+>' => 'reports/report/clientreportxls',
                'sold/expense/solded/<id:\d+>' => 'sold/expense/solded',
                'sold/expense/existsold/<id:\d+>' => 'sold/expense/existsold',
                'sold/orders/savelist/<id:\d+>' => 'sold/orders/savelist',
                'sold/orders/list/<id:\d+>' => 'sold/orders/list',
                'sold/expense/nakladnaya/<id:\d+>' => 'sold/expense/nakladnaya',
                'sold/orders/refreshd/<id:\d+>' => 'sold/orders/refreshd',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'forceCopy' => FALSE,          
        ],
        
    ],
    'params' => $params,
];
