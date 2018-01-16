<?php
namespace frontend\controllers;

use Yii;
use common\models\Bonus;
use common\models\BonusSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BonusController implements the CRUD actions for Bonus model.
 */
class BonusController extends LdBaseController
{
    
    public function actionIndex()
    {
        $searchModel = new BonusSearch();
        $searchModel->member_id = $this->user->id;
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }



    /**
     * Displays a single Bonus model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($today_time)
    {
        $searchModel = new BonusSearch();
        $searchModel->today_time = $today_time;
        $searchModel->member_id = $this->user->id;
        $dataProvider = $searchModel->searchView(Yii::$app->request->queryParams);

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    
}
