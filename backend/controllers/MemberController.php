<?php

namespace backend\controllers;

use Yii;
use common\models\Member;
use common\models\Relationship;
use common\models\MemberSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helps\tools;
use yii\web\ErrorHandler;
use common\models\MemberInfo;
use common\models\Parameter;
use PHPExcel;
use PHPExcel_IOFactory;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends LdBaseController
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
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->searchActivate(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionShop()
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->searchAgentActivate(Yii::$app->request->queryParams);
        return $this->render('shop', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionActivate($activate)
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['activate'=>$activate]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'activate'=>$activate,
        ]);
    }

    public function actionUpglevel($id)
    {
        $model = Member::find()->where(['id' => $id])->select('id, g_level')->one();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return null;
        } else {
            return $this->renderAjax('upglevel', ['model' => $model]);
        }
    }

    // 设置服务中心
    public function actionUpAg($id)
    {
        $model = Member::find()->where(['id' => $id])->select('id, is_agent')->one();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return null;
        } else {
            return $this->renderAjax('up-ag', ['model' => $model]);
        }
    }

    /**
     * Displays a single Member model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Member();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Member model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionFrontendLogin()
    {
        $id = Yii::$app->request->get('uid');
        $member = Member::findOne($id);
        if (!$member) {
            exit('参数不正确');
        }
        $member->auto_login_token = md5(time().'ldrj'.rand(999, 999999));
        if (!$member->save()) {
            exit('会员状态修改失败');
        }
        return $this->redirect(Yii::$app->params['frontendUrl'].'/index.php/site/admin-login?uid='.$id.'&token='.$member->auto_login_token);
    }

    // 已注册但未开通的会员
    public function actionNotOpen()
    {
        $searchModel = new MemberSearch();
        $searchModel->activate = 0; //开通未注册的状态为0
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('not-open', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /** 批量操作 */
    public function actionBatch()
    {
        $id = Yii::$app->request->post('id');
        $val = Yii::$app->request->post('val');
        if ($val == 'audit'){
            $isPay = 1;
        } else {
            $isPay = 2;
        }
        if (empty($id)){
            return tools::jsonError('请选择记录');
        }
        $models = Member::find()->where(['in', 'id', $id])->all();
        $tag = true;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            foreach ($models as $k => $var) {
                $i = $k;
                $result = $var->openUser($isPay);
                if (!$result) {
                    $tag = false;
                    break;
                }
            }
            if ($tag) {
                $transaction->commit();  /*提交事物*/
                return tools::jsonSuccess('审核成功');
            } else {
                return tools::jsonError('审核失败:'. $var->error_msg);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();/*回滚*/
            return tools::jsonError($e->getMessage());
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

    // 修改会员资料
    public function actionUpdateInfo($id)
    {  
        $model = MemberInfo::find()->where(['member_id' => $id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return null;
        } else {
            return $this->renderAjax('update-info', ['model' => $model]);
        }  
    }

    // 锁定会员
    public function actionLock()
    {
        $id = yii::$app->request->get('id');
        $model = Member::find()->select('id,is_lock')->where(['id'=>$id])->one();
        if($model->is_lock==0){
            $model->is_lock = 1;
            if($model->save()){
                Yii::$app->session->setFlash('success', '锁定成功');
                return $this->redirect(['index']);
            }
        }else{
            $model->is_lock = 0;
            if($model->save()){
                Yii::$app->session->setFlash('success', '解锁成功');
                return $this->redirect(['index']);
            }
        }

    }

    //重置密码
    public function actionResetPassword()
    {   
        $id = Yii::$app->request->post('id');
        $model = Member::find()->where(['id' => $id])->one();
        if ($model) {
            $model->setPassword(111111);
            $model->setPasswordTwo(222222);
            $model->generateAuthKey();
            $model->save();
            return tools::jsonSuccess('重置密码成功');
        }
        return tools::jsonError('重置密码失败');
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
     public function actionExport(){   
        //导出excel
        //$data =Yii::$app->request->get();
        $id=yii::$app->request->get();
        $objectPHPExcel = new PHPExcel();
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->searchActivate($id);
        $where = $dataProvider->query->where;
        $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $titles = ['用户名','所属报单中心','推荐人','姓名','手机号','激活时间','会员级别','代理级别','消费积分','注册积分','股权积分','基金积分','购物积分','复投积分','锁定','是否为服务中心'];
        $list=Member::find()->joinWith('memberInfo')->joinWith('relationship')->joinWith('account')->where($where)->orderBy('id asc ')->all();
        $i=1;
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);//改变此处设置的长度数值 
        foreach ($titles as $k => $v) {
                 $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[$k].$i,$v);
        }
        foreach ($list as $k => $var) {
            $i++;
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[0].$i,$var['username'].' ');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[1].$i,Member::getMemberName($var['shop_id']));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[2].$i,Member::getMemberName($var['relationship']['re_id']));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[3].$i,$var['memberInfo']['name'].' ');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[4].$i,$var['memberInfo']['phone'].' ');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[5].$i,date('Y-m-d H:i:s',$var['activate']));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[6].$i,Parameter::getUlevelName($var['u_level']));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[7].$i,Parameter::getGlevelName($var['g_level']));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[8].$i,$var['account']['account3']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[9].$i,$var['account']['account4']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[10].$i,$var['account']['account5']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[11].$i,$var['account']['account6']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[12].$i,$var['account']['account7']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[13].$i,$var['account']['account8']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[14].$i,$var['is_lock']==0?'否':'是');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[15].$i,$var['is_agent']==0?'否':'是');


        }


        ob_start();
    
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'会员信息'.date("Y年m月j日").'.xls"');

        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');

        $objWriter->save('php://output');

    }
}
