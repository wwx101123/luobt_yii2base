<?php

namespace backend\controllers;

use Yii;
use common\models\Recharge;
use common\models\RechargeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helps\tools;
/**
 * RechargeController implements the CRUD actions for Recharge model.
 */
class RechargeController extends LdBaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Recharge models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RechargeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model =new Recharge;
        if($model->load(yii::$app->request->post())){
            $model->state = 0;
            $model->create_time = time();
            if($model->save()){
               return $this->redirect(['index']);
            }
        }
        return $this->render('index', [
            'model' =>$model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

// 充值审核  
    public function actionAudit(){

       $id=Yii::$app->request->post('id');
       $models=Recharge::find()->where(['in','id',$id])->andWhere(['state'=>0])->all();
       $check = false;
       $connection = Yii::$app->db;
       $transaction = $connection->beginTransaction();
       try {
            foreach ($models as $k => $var) {
                $var->state = 1;
                $var->confirm_time=time();
                $var->ChongZhi();
            }
            $transaction->commit();/*提交事物*/
            return tools::jsonSuccess('审核成功');
        }
        catch (\Exception $e) {
           $transaction->rollBack();/*回滚*/
            return tools::jsonError($e->getMessage());
        }
     }

            //批量删除

    public function actionRefuse(){
        $id=yii::$app->request->post('id');
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            Recharge::deleteAll(['and',['in','id',$id],['state'=>0]]);
            $transaction->commit();/*提交事物*/
            return tools::jsonSuccess('删除成功');
            
        }
        catch (\Exception $e) {
            $transaction->rollBack();/*回滚*/
            return tools::jsonError($e->getMessage());

        }
    } 
    /**
     * Creates a new Recharge model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Recharge();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Recharge model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Recharge model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
