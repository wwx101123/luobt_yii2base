<?php

namespace backend\controllers;

use Yii;
use common\models\Sort;
use backend\models\SortSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * SortController implements the CRUD actions for Sort model.
 */
class SortController extends LdBaseController
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
     * Lists all Sort models.
     * @return mixed
     */
    // public function actionIndex()
    // {
    //     $searchModel = new SortSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }


    public function actionIndex()
    {
        $model = Sort::getSortList();
        // echo "<pre>";
        // var_dump(Sort::getDropDownList());exit;

        return $this->render('Index',['model'=>$model]);

    }

    /**
     * Displays a single Sort model.
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
     * Creates a new Sort model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {   
        $id = Yii::$app->request->get('id');
        $model = new Sort();
        $model->parent_id = $id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sort model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sort model.
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
     * Finds the Sort model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sort the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sort::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
     public function actionGetList()
    {   
        $id = Yii::$app->request->get('id');
        if ($id > 0) {
        $list =  Sort::getSortList($id);
        $array = \yii\helpers\ArrayHelper::map($list, 'id', 'sort_name');
        return \yii\helpers\Html::renderSelectOptions('option',$array);
        }
    }
}
