<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use common\models\Member;
use common\models\MemberInfo;
use common\models\MemberSearch;
// use common\models\Params;

class MemberController extends LdBaseController
{
	public function actionInfo()
	{
		$member = Member::find()->joinWith('account')->where(['{{%member}}.id'=>$this->user->id])->one();
		return $this->render('info',['member'=>$member]);
	}

	public function actionMyReg()
	{
		$searchModel = new MemberSearch();
        $dataProvider = $searchModel->searchRegMember(Yii::$app->request->queryParams);

        return $this->render('my-reg', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	public function actionUpdate(){
		$model = MemberInfo::find()->where(['member_id'=>$this->user->id])->one();
		if($post = yii::$app->request->post()){
			$model->load($post);
			if($model->update()){
				//$this->success('修改成功');
				Yii::$app->session->setFlash('info', '修改信息成功!');

			}
		}
		return $this->render('update',['model'=>$model]);
	}
}