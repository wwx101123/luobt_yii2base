<?php

namespace backend\controllers;

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
     * Lists all AccountHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'=>$searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


  




}
