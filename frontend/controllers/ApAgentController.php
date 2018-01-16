<?php

namespace frontend\controllers;

use Yii;
use common\models\ApAgent;
use common\models\ApAgentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Member;
/**
 * ApAgentController implements the CRUD actions for ApAgent model.
 */
class ApAgentController extends LdBaseController
{

    /**
     * Lists all ApAgent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ApAgentSearch();
        $searchModel->member_id = $this->user->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCreate()
    {
        try {
            $g_level = Member::find()->where(['>=','g_level', 1])->andwhere(['id'=>$this->user->id])->one();
            if (!$g_level) {
                throw new \Exception('经理级别以上才可申请！', 1);
            }
            $model = Member::find()->where(['is_agent'=>1])->andwhere(['id'=>$this->user->id])->one();
            if($model){
                throw new \Exception('您已经是服务中心,无需继续申请！', 1);
            }

            $ap_model= ApAgent::find()->where(['state'=>0])->andwhere(['member_id'=>$this->user->id])->one(); 
            if($ap_model){
                throw new \Exception('您正在申请,请耐心等待！', 1);
            }
            $data = new ApAgent;
            $data->member_id = $this->user->id;
            $data->create_time = time();
            $data->confirm_time = 0;
            $data->state = 0;
            if(!$data->save()){
                throw new \Exception('申请提交失败！', 1);
            }
            $this->success('申请提交成功，请耐心等待审核！');
            
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        return $this->redirect(['index']);

    }

    protected function findModel($id)
    {
        if (($model = ApAgent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
