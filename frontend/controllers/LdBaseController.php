<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use common\models\Parameter;
// use common\models\Params;

class LdBaseController extends \yii\web\Controller
{

    public $user;

    public $layout = "main_inside_banner_img"; //设置使用的布局文件

    public function init()  
    {
        $this->user = Yii::$app->user->identity;
        $checkPassword = yii::$app->session['password-two'];
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->is_lock == 1 ) {
            if(yii::$app->session['admin-login']!=1){
                Yii::$app->user->logout();
            }

        }
        if (!Yii::$app->user->isGuest &&  Parameter::getFrontendSwitch() == 1) {
            if(Yii::$app->session['admin-login'] != 1){
                Yii::$app->user->logout();
            }
        }
        
        if(!Yii::$app->user->isGuest && $checkPassword != 1 && Yii::$app->session['admin-login'] != 1){
            $actionID=Yii::$app->request->pathInfo;
            // var_dump($actionID);die;
            $un_action=['to-cash','account-change','recharge','agent','account-transfer','fenhong'];
            if($actionID != 'site/check-password' && in_array($this->route,$un_action)){
                return $this->redirect(['/site/check-password','backUrl'=>$actionID]);
            }
        }
        // if (!Yii::$app->user->isGuest) {
        //     $lock = Params::getVal(23);
        //     if ($lock == 1) {
        //         Yii::$app->user->logout();
        //     }
        // }
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                // 'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['login','check-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['captcha'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    // [
                    //     'actions' => ['admin-login'],
                    //     'allow' => true,
                    //     'roles' => ['?'],
                    // ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                   
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function renderJsonObject($msg = '', $status = 1, $data = []) 
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['msg' => $msg, 'status' => $status, 'data' => $data];
    }

    private function MulitarrayEncode(&$array){
       if(is_array($array)){
            foreach ($array as $key => $value )
            {
                if(is_array($value)){
                    $array[$key] = $this->MulitarrayEncode($value);
                }
                else{
                    $array[$key] = Html::encode($value);
                }
            }
            return $array;
       }
    }

    public function renderJsonSafe($data=[]) 
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $this->MulitarrayEncode($data);
        return $data;
    }

    public function renderJson($data=[]) 
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function renderJsonSuccess($msg='', $data=[])
    {
        return $this->renderJsonObject($msg,1,$data);
    }

    public function renderJsonError($msg='', $data=[])
    {
        return $this->renderJsonObject($msg,0,$data);
    }

    public function success($msg='')
    {
        $this->setFlash('success', $msg);
    }

    public function error($msg='')
    {
        $this->setFlash('error', $msg);
    }

    public function setFlash($key='', $msg='')
    {
        Yii::$app->session->setFlash($key, $msg);
    }

}
