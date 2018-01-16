<?php
namespace backend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\SignupForm;
use common\models\Member;

/**
 * Site controller
 */
class SignupController extends LdBaseController
{

	public function actionIndex()
	{
        return $this->redirect(['/tree/tree']);
        
		$model = new SignupForm();
        $fatherId = Yii::$app->request->get('father_id');
        if ($fatherId) {
            $father = Member::findOne($fatherId);
            if ($father) {
                $model->father_name = $father->username;
            }
        }
        $model->area = Yii::$app->request->get('tp');
        $model->username = rand(100000, 9999999);
        $model->shop_name = 'demo';
        $model->re_name = 'demo';
        $model->password = '111111';
        $model->name = '张三';
        $model->code = '456000000000000000';
        $model->phone = '15555555555';
		$model->setScenario('def');
        if ($model->load(Yii::$app->request->post()) && $user = $model->signup()) {
        	// $this->success('注册成功');
            return $this->redirect(['signup-success', 'id'=>$user->id]);
            // return $this->refresh();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
	}

    public function actionSignupSuccess($id)
    {
        $model = Member::findOne($id);
        return $this->render('signup-success', ['model'=>$model]);
    }

}