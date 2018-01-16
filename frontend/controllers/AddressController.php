<?php

namespace frontend\controllers;

use Yii;
use common\models\Address;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Request;
use common\helps\tools;
use common\models\Area;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/**
 * AddressController implements the CRUD actions for Address model.
 */
class AddressController extends LdBaseController
{

    /**
     * Lists all Address models.
     * @return mixed
     */
    public function actionIndex()
    {   
        // $this->layout = 'column1';
        $user_id = yii::$app->user->id;

        $id = Yii::$app->request->get('id')?Yii::$app->request->get('id'):'';
        $list = Address::find()->orderBy('id desc')->where(['user_id'=>$user_id])->asArray()->all();


        return $this->render('index', [
            'list' => $list,
            'id'=>$id
        ]);
    }

    public function actionMyAddress()
    {   
        $this->layout = 'column1';
        $user_id = yii::$app->user->id;

        $id = Yii::$app->request->get('id')?Yii::$app->request->get('id'):'';
        $list = Address::find()->orderBy('id desc')->where(['user_id'=>$user_id])->asArray()->all();


        return $this->render('myAddress', [
            'list' => $list,
            'id'=>$id
        ]);
    }

    /**
     * Displays a single Address model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
  /**
     * Creates a new Address model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {   
        $id = Yii::$app->request->get('id')?Yii::$app->request->get('id'):'';
        $model = new Address();
        $model->user_id = yii::$app->user->id;
        $model->scenario = 'add';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // if (empty($id)) {
            //     return $this->redirect(['address/index']);
            // }else{
                // return $this->redirect(['order/confirm-order','id'=>$id]);
            return null;
            // }
        } else {

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

 
    /**
     * Updates an existing Address model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
       $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return null;
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionSetDefualt($id)
    {   
        Address::updateAll(['status'=>0],'user_id = :ID',[':ID'=>Yii::$app->user->identity->id] );
         $model = $this->findModel($id);
         $model->status = 1;
         $model->update();
    }

    /**
     * Deletes an existing Address model.
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
     * Finds the Address model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Address the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Address::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionChooseAddress($id)
    {   


        $data = $this->findModel($id);
        $data->status = 1;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            if ($data->save(false)) {
                 $resulf=Address::updateAll(['status'=>0],'user_id = '.$data->user_id.' and id !='.$id);

                 if ($resulf) {
                    $transaction->commit();
                    return tools::jsonSuccess('成功');
                 }else{
                    return tools::jsonSuccess('失败');
                 }

            }else{
                return tools::jsonSuccess('失败');
            }
        } catch (Exception $e) {
             $transaction->rollBack();
        }
    }
}
