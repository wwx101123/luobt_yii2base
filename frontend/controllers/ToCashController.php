<?php

namespace frontend\controllers;

use Yii;
use common\models\ToCash;
use common\models\ToCashSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Account;
use common\models\Bankcard;
/**
 * ToCashController implements the CRUD actions for ToCash model.
 */
class ToCashController extends LdBaseController
{

    /**
     * Lists all ToCash models.
     * @return mixed
     */
    public function actionIndex()
    {

        $model= new ToCash;
        $model->scenario ='f_add';
        $searchModel = new ToCashSearch();
		$searchModel->member_id = $this->user->id;
        $dataProvider = $searchModel->searchUser(Yii::$app->request->queryParams, $this->user->id);
        $model->bankcard_id = Bankcard::getDefaultCardId();
        if($model->load(yii::$app->request->post())){
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->member_id = $this->user->id;
                $model->create_time = time();
                $model->state= 0;
                $model->addBankCard();//银行卡 属性赋值
                $model->attMoney();// 金额 属性赋值
                if($model->save()){
                    $model->refresh();
                    $this->success('提现成功！');
                    $transaction->commit();
                    return $this->redirect('index');
                }
            } catch (\Exception $e) {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error', $e->getMessage());  
            }
        }
        $account_model = new Account;
        $account3 = Account::find()->where(['member_id' => $this->user->id])->one()->account3;
        $account_model->account3 = $account3;
        return $this->render('index', [
            'account3' => $account3,
            'account_model' => $account_model,
            'model'=>$model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

  
    /**
     * Finds the ToCash model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToCash the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToCash::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
