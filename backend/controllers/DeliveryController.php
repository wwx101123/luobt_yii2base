<?php

namespace backend\controllers;
use yii;
use backend\models\OrderSearch;
class DeliveryController extends LdBaseController
{
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $searchModel->delivery = 0;
        $searchModel->order_status = 1;
        // $searchModel->s_date = date('Y-m-d 00:00');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionComplete()
    {
        $searchModel = new OrderSearch();
        $searchModel->delivery = 1;
        $searchModel->order_status >= 1;
        // $searchModel->s_date = date('Y-m-d 00:00');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('complete', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
