<?php

namespace frontend\controllers;

use Yii;
use common\models\Bankcard;
use common\models\BankcardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Region;
use common\models\MemberInfo;

/**
 * BankcardController implements the CRUD actions for Bankcard model.
 */
class BankcardController extends LdBaseController
{

 public function actions()
    {
        $actions=parent::actions();
        $actions['get-region']=[
            'class'=>\chenkby\region\RegionAction::className(),
            'model'=>\common\models\Region::className()
        ];
        return $actions;
    }
    /**
     * Lists all Bankcard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BankcardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bankcard model.
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
     * Creates a new Bankcard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bankcard();
        $info = MemberInfo::find()->where(['member_id'=>$this->user->id])->one();
        $model->username = $info->name;
        if ($model->load(Yii::$app->request->post())) {
            try {
                if(empty($model->city)){
                    throw new \Exception("城市不能为空！", 1);
                }
                $model->province= Region::getCityName($model->province);
                $model->city = Region::getCityName($model->city);
                $model->member_id= $this->user->id;
                if(!$model->save()){
                    throw new \Exception("保存失败！", 1);
                }
                $this->success('添加成功');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }
            return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing Bankcard model.
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
     * Deletes an existing Bankcard model.
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
     * Finds the Bankcard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bankcard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bankcard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
