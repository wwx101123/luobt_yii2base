<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\SignupForm;
use common\models\Member;
use common\models\Agreement;
use common\models\Product;
/**
 * Site controller
 */
class SignupController extends LdBaseController
{

	public function actionIndex($tp = 0)
	{
		$model = new SignupForm();
        $fatherId = Yii::$app->request->get('father_id');
        if ($fatherId) {
            $father = Member::findOne($fatherId);
            if ($father) {
                $model->father_name = $father->username;
            }
        }
        $model->area = $tp;  // 默认注册 左区 为所在区域
        $model->username = rand(100000, 9999999);
        //print_r($model->username);exit;
        if($this->user->is_agent == 1){
            $model->shop_name = $this->user->username;
        }
        $model->re_name = $this->user->username;
        $model->password = '111111';
        $model->password_hash_confirm = '111111';
        $model->password_two = '222222';
        $model->password_hash_two_confirm = '222222';
        $model->name = '张三';
        $model->code = '456000000000000000';
        $model->phone = '15555555555';
		$model->setScenario('def');
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        	// $this->success('注册成功');
            // return $this->refresh();
            return $this->render('get-inform',['model'=>$model]);           
        }
        $agreement = Agreement::findOne(1)->content;
        return $this->render('index', [
            'model' => $model,
            'agreement' => $agreement,
        ]);
	}
    
    public function actionGetInform(){
        $model = new SignupForm;
        if($model->load(Yii::$app->request->post()) && $user= $model->signup()){
            $this->success('注册成功');
            return $this->redirect(['signup-success', 'id'=>$user->id]);
        }
        else{
            return $this->redirect(['index']);
        }
    }
    public function actionSignupSuccess($id)
    {
        $model = Member::findOne($id);
        $is_agent=$this->user->is_agent;
        return $this->render('signup-success', ['model'=>$model,'is_agent'=>$is_agent]);
    }

    public function actionGetRename()
    {
        $username = Yii::$app->request->post('name');
        $member = Member::find()->where('username=:username',[':username'=>$username])->one();
        if (!$member) {
            return $this->renderJsonError('会员不存在');
        }
        return $this->renderJsonSuccess($member->memberInfo->name);
    }

    public function actionAgreement()
    {
        $model = Agreement::findOne(1);
        return $this->render('agreement', ['model'=>$model]);
    }

     public function actionGetReg()
    {
        $level=Yii::$app->request->post('level');
        $list = Product::getGoodsList(Product::IS_REG,$level);
        return $this->renderJsonSuccess('添加成功',$list);
    }

}