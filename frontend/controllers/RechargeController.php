<?php

namespace frontend\controllers;

use Yii;
use common\models\Recharge;
use common\models\RechargeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RechargeController implements the CRUD actions for Recharge model.
 */
class RechargeController extends LdBaseController
{

    /**
     * Lists all Recharge models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RechargeSearch();
        $searchModel->member_id = $this->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model=new Recharge;
        $model->scenario ='f_add';
        if($model->load(yii::$app->request->post())){

            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->pay_time = strtotime($model->pay_time);
                $model->member_id= $this->user->id;
                $model->create_time = time();
                $model->state= 0;
                if($model->save()){
                    $model->refresh();
                    $this->success('充值成功！');
                    $transaction->commit();
                    return $this->redirect('index');
                }
            } catch (\Exception $e) {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error', $e->getMessage());  
            }
        }
        return $this->render('index', [
                'model' =>$model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

   

    /**
     * Finds the Recharge model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Recharge the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Recharge::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
