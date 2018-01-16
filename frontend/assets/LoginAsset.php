<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // 'statics/css/style.css',
        // 'statics/css/bs_style.css',

        'statics/css/login.css',
        'statics/css/style.css',
        // 'statics/css/bootstrap.css',
    ];
    public $js = [
        // 'statics/js/jquery-1.11.1.min.js',
        // 'statics/js/classie.js',
        // 'statics/js/layer/layer.js',
        // 'statics/js/site.js',

        // 'statics/js/classie.js',
        // 'statics/js/jquery-1.11.1.min.js',
        // 'statics/js/jquery.slide.js',
        // 'statics/js/slides.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
    //JQ在head 加载
    public $jsOptions = [
    'position' => \yii\web\View::POS_HEAD
    ];
}
     
