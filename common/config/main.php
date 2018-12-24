<?php
return [
    'aliases' => array(
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',

    ),
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'modules' => [
        'calc' => [
            'class' => 'app\modules\calc\calc',
        ],
        'sold' => [
            'class' => 'app\modules\sold\sold',
        ],
        'accounting' => [
            'class' => 'app\modules\accounting\accounting',
        ],
        'reports' => [
            'class' => 'app\modules\reports\reports',
        ],
    ],
];
