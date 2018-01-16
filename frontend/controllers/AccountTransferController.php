<?php

namespace frontend\controllers;

use Yii;
use common\models\AccountTransfer;
use common\models\AccountTransferSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Member;
/**
 * AccountTransferController implements the CRUD actions for AccountTransfer model.
 */
class AccountTransferController extends LdBaseController
{

    /**
     * Lists all AccountTransfer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model= new AccountTransfer;
        $searchModel = new AccountTransferSearch();
        $searchModel ->out_id = $this->user->id;
        $searchModel ->into_id = $this->user->id;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if($model->load(yii::$app->request->post())){
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->create_time = time();
                $model->out_id = $this->user->id;
                $model->out_name = $this->user->username;
                $model->into_money = $model->out_money;
               if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', '转帐成功！');
                    $model->refresh(); 
                    $transaction->commit();
                    return $this->redirect('index');
                }
            } catch (\Exception $e) {
                $transaction->rollback();
                Yii::$app->getSession()->setFlash('error', $e->getMessage());
            }
           
        }
        return $this->render('index', [
            'model'=>$model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionGetRename()
    {
        $username = Yii::$app->request->post('name');
        $member = Member::find()->where('username=:username',[':username'=>$username])->one();
        if (!$member) {
            return $this->renderJsonError('会员不存在');
        }
        return $this->renderJsonSuccess($member->memberInfo->name);
    }
    /**
     * Finds the AccountTransfer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccountTransfer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccountTransfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
