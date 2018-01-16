<?php

namespace frontend\controllers;

use Yii;
use common\models\Fenhong;
use common\models\FenhongSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\BonusCalc;
use common\models\Relationship;

/**
 * FenhongController implements the CRUD actions for Fenhong model.
 */
class FenhongController extends LdBaseController
{
    /**
     * Lists all Fenhong models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FenhongSearch();
        $searchModel->uid = $this->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFutou()
    {
        
        $errorMsg = $this->checkFutou();
        // $errorMsg = false;
        return $this->render('futou', ['errorMsg'=>$errorMsg]);
    }

    public function checkFutou()
    {
        $model = Fenhong::find()->where(['uid'=>$this->user->id])->orderBy(['qi'=>SORT_DESC])->one();

        if (!$model) {
            return '您当前不可复投';
        }
        if ($model->amount > $model->f_amount) {
            return '您上期的静态收益未到期';
        }
        $re = Relationship::find()->andWhere(['member_id'=>$this->user->id])->select('re_nums')->one();
        if ($re->re_nums < 1) {
            return '至少要直推1人才可复投';
        }
        return false;
    }

    public function actionFutouac()
    {
        $errorMsg = $this->checkFutou();
        if ($errorMsg) {
            $this->error($errorMsg);
            return $this->redirect('futou');
        }

        $errorMsg = BonusCalc::futou(Yii::$app->user->identity);
        if (!$errorMsg) {
            $this->success('复投成功');
            return $this->redirect('index');
        }
        else {
            $this->error($errorMsg);
            return $this->redirect('futou');
        }
    }

    /**
     * Finds the Fenhong model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fenhong the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fenhong::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
