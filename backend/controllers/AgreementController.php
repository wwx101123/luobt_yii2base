<?php

namespace backend\controllers;

use Yii;
use common\models\Agreement;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AgreementController implements the CRUD actions for Agreement model.
 */
class AgreementController extends LdBaseController
{


    /**
     * Lists all Agreement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Agreement::find()->one();
        if(empty($model)){
            $model= new Agreement;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['index']);
        } 
            
        return $this->render('update', [
                'model' => $model,
            ]);

    }
   
}
