<?php
namespace frontend\controllers;

use Yii;
use common\component\thrift\ThriftApi;
use yii\log\Logger;
/**
 * 会员中心
 */
class UserController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionChannel() 
    {
       // Yii::getLogger()->log("222222", 'profile', $category = 'application');
        //Yii::getLogger()->log("222222", $level = 'info', $category = 'application');
        //Yii::getLogger()->log("222222", ['info'], $category = 'application');


        //Yii::info("info,记录操作提示");
        //Yii::info("profile,记录操作提示");
        \Yii::beginProfile('myBenchmark');
           $params['selfPlatformCode']=10000;
           $transactionCode='SELF_PLATFORM';//交易码
           $appCode='W10090';
           $data=ThriftApi::api($params, $transactionCode, $appCode);
           echo "<pre>";print_r($data);echo "</pre>";
        \Yii::endProfile('myBenchmark');   
           \Yii::$app->getLog()->getLogger()->log($data, Logger::LEVEL_ERROR, $category = 'application');
    }
}