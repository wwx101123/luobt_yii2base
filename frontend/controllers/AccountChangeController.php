<?php

namespace frontend\controllers;

use Yii;
use common\models\AccountChange;
use common\models\Member;
use common\models\AccountChangeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Account;
/**
 * AccountChangeController implements the CRUD actions for AccountChange model.
 */
class AccountChangeController extends LdBaseController
{

    /**
     * Lists all AccountChange models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new AccountChangeSearch();
        $searchModel->member_id = $this->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $user = Member::findOne($this->user->id);
        $model = new AccountChange;
        $model->member_id =$this->user->id;
        if ($model->load(Yii::$app->request->post())){
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try{
                $model->create_time = time();
                $model->DisposalMoney();
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', '转换成功！');
                    $model->refresh(); 
                    $transaction->commit();
                    return $this->redirect('index');
                }
            }catch (\Exception $e) {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error', $e->getMessage());
            }
        }
        $accountModel = new Account;
        $account = Account::find()->where(['member_id' => $this->user->id])->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user,
            'model' => $model,
            'account' => $account,
            'accountModel' => $accountModel,
        ]);
    }

    /**
     * Displays a single AccountChange model.
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
     * Creates a new AccountChange model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccountChange();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AccountChange model.
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
     * Deletes an existing AccountChange model.
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
     * Finds the AccountChange model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccountChange the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccountChange::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
