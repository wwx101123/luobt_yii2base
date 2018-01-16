<?php

namespace frontend\controllers;

use Yii;
use common\models\AccountHistory;
use common\models\AccountHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccountHistoryController implements the CRUD actions for AccountHistory model.
 */
class AccountHistoryController extends LdBaseController
{
    /**
     * Lists all AccountHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountHistorySearch();
        $member_id = Yii::$app->user->getId();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $member_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the AccountHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccountHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccountHistory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
