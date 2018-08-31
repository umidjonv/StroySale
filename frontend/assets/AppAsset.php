<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        'css/bootstrap.css',
        'font/css/open-iconic-bootstrap.css',
        'css/jquery-ui.min.css',
        'css/jquery-ui.theme.min.css',
        'DataTables/datatables.css',
        'css/custom.css',
        
    ];
    public $js = [
        'js/jquery.min.js',
        'js/jquery-ui.min.js',
        'DataTables/datatables.js',
        'js/custom.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
