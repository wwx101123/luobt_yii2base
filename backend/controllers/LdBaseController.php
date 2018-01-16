<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use common\models\Parameter;
// use common\models\Params;

class LdBaseController extends \yii\web\Controller
{

    public $user;

    public function init()  
    {
        $this->user = Yii::$app->user->identity;
        $checkPassword = yii::$app->session['password-two'];

        // if(!Yii::$app->user->isGuest && $checkPassword!=1){
        //     $actionID=Yii::$app->request->pathInfo;
        //     // $un_action=['to-cash','account-change','recharge','agent','account-transfer','fenhong'];
        //     if($actionID!='site/check-password'){
        //         return $this->redirect(['/site/check-password','backUrl'=>$actionID]);
        //     }
        // }

    }


}
