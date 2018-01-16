<?php
namespace backend\controllers;

use Yii;
use common\models\Bonus;
use common\models\BonusSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helps\tools;
use common\models\BonusCalc;
use common\bonus\Fenhong;
use common\models\Parameter;
use yii\data\ActiveDataProvider;

/**
 * BonusController implements the CRUD actions for Bonus model.
 */
class BonusController extends LdBaseController
{
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
    
    public function actionIndex($today_time)
    {
        $searchModel = new BonusSearch();
        $searchModel->today_time = $today_time;
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexAll()
    {
        $today_time = Yii::$app->request->get('today_time');
        $searchModel = new BonusSearch();
        $dataProvider = $searchModel->searchAllDay(Yii::$app->request->queryParams);

        if ($today_time) {
            $dataProvider->query->where(['today_time' => $today_time]);
        }

        return $this->render('index-all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

     public function actionRatio()
    {
        $searchModel = new BonusSearch();
        $dataProvider = $searchModel->searchRatio(Yii::$app->request->queryParams);

        return $this->render('ratio', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }


    /**
     * Displays a single Bonus model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($today_time, $uid)
    {
        $searchModel = new BonusSearch();
        $searchModel->today_time = $today_time;
        $searchModel->member_id = $uid;
        $dataProvider = $searchModel->searchView(Yii::$app->request->queryParams);

        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionSettlementView()
    {
        $infoArr = Fenhong::getFenHongMember(); //获取分红的会员
        $yeji = Parameter::getValById(26);
        return $this->render('settlement-view', ['amount' => $infoArr[2], 'yeji' => $yeji]);
    }

    public function actionSettlement()
    {
        // ini_set('memory_limit','1024M');
        // set_time_limit(1800);
        $offset = Yii::$app->request->post('index');
        $limit = Yii::$app->request->post('oneLimit');
        $amount = Yii::$app->request->post('amount');
        $offset = 0;
        // $count = BonusCalc::fenhong(NULL,false,$offset,$limit);
        if ($amount > 0) {
            Fenhong::run($amount);
            BonusCalc::calcBonusList();
        }
        Parameter::updateAll(['val'=>0],['id'=>26]);
        $count = 1;
        return tools::jsonSuccess($count);
    }

    public function actionSettlementFenhongInfo(){
        // ini_set('memory_limit','1024M');
        // set_time_limit(1800);
        // $msg = BonusCalc::fenhong(NULL,true);
        $msg = 1;
        return tools::jsonSuccess($msg);
    }
    
}
