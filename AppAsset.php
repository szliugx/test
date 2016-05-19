<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    //全局css
    public $css = [
    ];
    //全局js
    public $js = [
    ];
    //依赖关系
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
     //定义按需加载JS方法，注意加载顺序在最后  
    public static function addScript($view, $jsfile) {  
        $view->registerJsFile('http://'.$_SERVER['SERVER_NAME'].str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])).$jsfile, [AppAsset::className(), 'depends' => 'frontend\assets\AppAsset']); 
    }  
     //定义按需加载JS方法，注意加载顺序在最后,加载在</head>之前
    public static function addHeadScript($view, $jsfile) {  
        $view->registerJsFile('http://'.$_SERVER['SERVER_NAME'].str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])).$jsfile, [AppAsset::className(), 'depends' => 'frontend\assets\AppAsset', "position"=> $view::POS_HEAD]); 
    }  
   //定义按需加载css方法，注意加载顺序在最后  
    public static function addCss($view, $cssfile) {  
        $view->registerCssFile('http://'.$_SERVER['SERVER_NAME'].str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])).$cssfile, [AppAsset::className(), 'depends' => 'frontend\assets\AppAsset']); 
    } 
}
