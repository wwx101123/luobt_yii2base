<?php

namespace frontend\controllers;

use Yii;
use common\models\Report;
use common\models\ReportMsg;
use common\models\ReportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends LdBaseController
{

    public function actions(){

        return [
            'ueditor'=>[
                'class' => 'common\widgets\ueditor\UeditorAction',
                'config'=>[
                    //上传图片配置
                    'imageUrlPrefix' => Yii::$app->params['imageUrlPrefix'], /* 图片访问路径前缀 */
                    'imagePathFormat' => "/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                    'uploadFilePath' => Yii::$app->params['upload_url'],
                ]
            ],
    
            'upload'=>[
                'class' => 'common\widgets\file_upload\UploadAction',     //这里扩展地址别写错
                'config' => [
                    'imageUrlPrefix'=>Yii::$app->params['imageUrlPrefix'],
                    'imagePathFormat' => "/{yyyy}{mm}{dd}/{time}{rand:6}",
                    'uploadFilePath' => Yii::$app->params['upload_url'],

                ]
            ]
        ];
    }
    /**
     * Lists all Report models.
     * @return mixed
     */
    public function actionIndex()
    {
      
        $searchModel = new ReportSearch();
        $searchModel->user_id = Yii::$app->user->identity->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Displays a single Report model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $model = Report::find()->where(['ld_report.id'=>$id])->joinWith('reportMsg')->one();
        $model_msg = new ReportMsg();
        $model_msg->name = Yii::$app->user->identity->username;
        $model_msg->report_id = $model->id;
        if ($model_msg->load(Yii::$app->request->post())) {
            if ($model_msg->UserReport()) {

                return $this->refresh();
            }else{

            }

        }
        return $this->render('view', [
            'model' => $model,
            'model_msg'=>$model_msg
        ]);
    }

    /**
     * Creates a new Report model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Report();
        $model->user_id = yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->addMsg()) {
             if ($model->save()) {
                return $this->redirect(['index', 'id' => $model->id]);
             } 
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }



    /**
     * Updates an existing Report model.
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
     * Deletes an existing Report model.
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
     * Finds the Report model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Report the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Report::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
