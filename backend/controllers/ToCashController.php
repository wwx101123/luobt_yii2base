<?php

namespace backend\controllers;

use Yii;
use common\models\ToCash;
use common\models\ToCashSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Account;
use common\helps\tools;
use common\models\Member;
use PHPExcel;
use PHPExcel_IOFactory;
/**
 * ToCashController implements the CRUD actions for ToCash model.
 */
class ToCashController extends LdBaseController
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
     * Lists all ToCash models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ToCashSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    // 提现审核
    public function actionAudit(){
        $ids=yii::$app->request->post('id');
        $model =ToCash::find()->where(['in','id',$ids])->andwhere(['state'=>ToCash::$states[0]])->all();
        if($model){
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                foreach ($model as $key => $v) {
                    $v->state =1;
                    $v->confirm_time=time();
                    if(!$v->update()){
                        throw new \Exception("审核失败");
                    }
                }
                $transaction->commit();/*提交事物*/
                return tools::jsonSuccess('审核成功');
            } catch (\Exception $e) {
                $transaction->rollback();
                return tools::jsonError($e->getMessage());
            }
        }
        return tools::jsonSuccess('审核成功');
        
    }
    //批量打回
    public function actionRefuse(){
            //批量删除
        $id=yii::$app->request->post('id');
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $model = ToCash::find()->where(['in','id',$id])->andWhere(['state'=>0])->all();
        try {
            foreach ($model as $key => $v) { 
                
                    $v->state=2;
                    $v->confirm_time=time();
                    Account::addAccount($v->member_id, $v->to_money, 'account3', $bz='提现打回');

                    if (!$v->update()) {
                        throw new \Exception("审核失败");
                    }
            }
            $transaction->commit();/*提交事物*/
            return tools::jsonSuccess('审核成功');
            
        }
        catch (\Exception $e) {
            $transaction->rollBack();/*回滚*/
            return tools::jsonError($e->getMessage());

        }
    }

    /**
     * Finds the ToCash model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ToCash the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ToCash::findOne($id)) !== null) {
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
        $searchModel = new ToCashSearch();
        $dataProvider = $searchModel->search($id);
        $where = $dataProvider->query->where;
        $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $titles = ['会员编号','开户银行','银行卡号','开户姓名','开户地址','交易金额','手续费','实发金额','提现类型','提现时间','审核时间','审核状态'];
        $list=ToCash::find()->where($where)->orderBy('id asc ')->all();
        $i=1;
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);//改变此处设置的长度数值 
        foreach ($titles as $k => $v) {
                 $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[$k].$i,$v);
        }
        foreach ($list as $k => $var) {
            $i++;
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[0].$i,Member::getMemberName($var['member_id']));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[1].$i,$var['bankname']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[2].$i,$var['number'].' ');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[3].$i,$var['username']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[4].$i,$var['address']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[5].$i,$var['to_money']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[6].$i,$var['tax']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[7].$i,$var['real_money']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[8].$i,ToCash::getTypeName($var['type']));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[9].$i,date('Y-m-d H:i:s',$var->create_time));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[10].$i,$var['confirm_time']==0?'':date('Y-m-d H:i:s',$var->confirm_time));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[11].$i,ToCash::$states[$var['state']]);

        }


        ob_start();
    
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'提款信息'.date("Y年m月j日").'.xls"');

        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');

        $objWriter->save('php://output');

    }
}
