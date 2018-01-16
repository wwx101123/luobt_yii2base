<?php

namespace frontend\controllers;

use Yii;
use common\models\Member;
use common\models\MemberSearch;
use common\models\Account;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AgentController implements the CRUD actions for Member model.
 */
class AgentController extends LdBaseController
{

    /**
     * Lists all Member models.
     * @return mixed
     */
    public function actionUnactivate()
    {
        $searchModel = new MemberSearch();
        $member = $this->user;
        $searchModel->shop_id = $member->id;
        $dataProvider = $searchModel->searchUnActivate(Yii::$app->request->queryParams);
        return $this->render('unactivate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'member' => $member,
        ]);
    }

    public function actionMember()
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->searchShopMember(Yii::$app->request->queryParams);

        return $this->render('member', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBatch()
    {
        $ids=Yii::$app->request->post('id');
        if (empty($ids)){
            return $this->renderJsonError('请选择记录');
        }
        $val=Yii::$app->request->post('val');
        switch ($val) {
            case 'audit':
                return $this->activate($ids);
                break;
            case 'del':
                return $this->del($ids);
                break;    
            default:
                return $this->renderJsonError('错误操作！');
                break;
        }
        
    }

    private function activate($ids)
    {
        $models=Member::find()->where(['in','id',$ids])->all();
        $agent_user = Member::find()->where(['id'=>$this->user->id])->one();/*服务中心*/
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $loginMemberId = Yii::$app->user->getId();
            foreach ($models as $k => $var) {
                Account::baodan($loginMemberId, -$var->cpzj, '开通会员'.$var->username);
                $var->openUser();
            }
            $transaction->commit();/*提交事物*/
            return $this->renderJsonSuccess('审核成功');
        } catch (\Exception $e) {
            $transaction->rollBack();/*回滚*/
            return $this->renderJsonError('审核失败:'.$e->getMessage());
        }
    }

    private function del($ids)
    {
        $models=Member::find()->where(['in','id',$ids])->all();
        $agent_user = Member::findOne($this->user->id);/*服务中心*/
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            foreach ($models as $k => $var) {
                $var->delete();
            }
            $transaction->commit();/*提交事物*/
            return $this->renderJsonSuccess('删除成功');
        } catch (\Exception $e) {
            $transaction->rollBack();/*回滚*/
            return $this->renderJsonError('删除失败:'.$e->getMessage());
        }
    }



    /**
     * Deletes an existing Member model.
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
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
