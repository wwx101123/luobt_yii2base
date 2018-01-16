<?php

namespace backend\controllers;

use Yii;
use common\models\ApAgent;
use common\models\ApAgentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Member;
use common\helps\tools;

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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // 审核
    public function actionAudit()
    {
        $ids = Yii::$app->request->post('id');
        $model = ApAgent::find()->where(['in', 'id', $ids])->andwhere(['state' => 0])->all();
        if ($model) {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                foreach ($model as $key => $v) {
                    $v->state = 1; //审核过后把状态修改为1表示通过审核
                    $v->confirm_time = time();
                    if ($v->update()) {
                        $member = Member::find()->where(['id' => $v->member_id])->one();
                        $member->is_agent = 1; //把申请为服务中心的会员设置为服务中心
                        $member->update();
                    } else {
                        throw new \Exception("审核失败");
                    }
                }
                $transaction->commit(); /*提交事物*/
                return tools::jsonSuccess('审核成功');
            } catch (\Exception $e) {
                $transaction->rollback();
                return tools::jsonError($e->getMessage());
            }
        }
        return tools::jsonSuccess('审核成功');
        
    }

    //批量打回
    public function actionRefuse()
    {
        $id = Yii::$app->request->post('id');
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $model = ApAgent::find()->where(['in', 'id', $id])->andWhere(['state' => 0])->all();
        try {
            foreach ($model as $key => $v) {     
                $v->state = 2; //state=2 表示 拒绝 用户申请成为服务中心
                $v->confirm_time = time();
                if (!$v->update()) {
                    throw new \Exception("审核失败");
                }
            }
            $transaction->commit(); /*提交事物*/
            return tools::jsonSuccess('审核成功');   
        } catch (\Exception $e) {
            $transaction->rollBack();/*回滚*/
            return tools::jsonError($e->getMessage());
        }
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
