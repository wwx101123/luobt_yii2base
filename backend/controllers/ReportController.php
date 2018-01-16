<?php

namespace backend\controllers;

use Yii;
use common\models\Report;
use common\models\ReportMsg;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Member;
/**
 * ReportController implements the CRUD actions for Report model.
 */
class ReportController extends LdBaseController
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
     * Lists all Report models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Report::find()->joinWith('member');
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * Displays a single Report model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $model = Report::find()->where(['ld_report.id'=>$id])->with('reportMsg')->one();
        $model_msg = new ReportMsg();
        $model_msg->name = '管理员';
        $model_msg->report_id = $model->id;
        if ($model_msg->load(Yii::$app->request->post()) ) {
            if ($model_msg->AdminReport()) {

                return $this->refresh();
            }else{

            }

        }

        return $this->render('view', [
            'model' => $model,
            'model_msg' => $model_msg
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
        if ($model->load(Yii::$app->request->post())) {
           $user = Member::find()->where(['username' => $model->user_name])->one();
            if ($user) {
                $model->user_id = $user->id;
                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
           Yii::$app->getSession()->setFlash('error', '没有该会员');
           return $this->render('create', [
                'model' => $model,
            ]);
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

    public function actionClose($id)
    {
        $model = $this->findModel($id);
        $model->status = 1;
        if ($model->save()) {
            Yii::$app->getSession()->setFlash('success', '关闭成功');
        } else {
            Yii::$app->getSession()->setFlash('success', '关闭失败');
        }
        return $this->redirect(['index']);
    }
    
}
