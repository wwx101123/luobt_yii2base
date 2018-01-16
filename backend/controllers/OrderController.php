<?php

namespace backend\controllers;

use Yii;
use common\models\Order;
use common\models\Product;
use common\models\Member;
use common\models\OrderGoods;
use backend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helps\tools;
use yii\helpers\Url;
use common\models\OrderAction;
use PHPExcel;
use PHPExcel_IOFactory;
use common\WxPay\WxPayRefund;
use common\WxPay\WxPayApi;
/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends LdBaseController
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
         // $searchModel->delivery = 0;
        $searchModel->order_status = 0;
        // $searchModel->s_date = date('Y-m-d 00:00');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $count = Order::find()->where(['order_status'=>0])->count();


        return $this->render('index', [
            'searchModel' => $searchModel,
            'count' => $count,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $goods_list = OrderGoods::find()->where(['order_id'=>$id])->asArray()->all();
        $action_list = OrderAction::find()->where(['order_id'=>$id])->asArray()->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'goods_list'=>$goods_list,
            'action_list'=>$action_list,
            'id'=>$id,
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return null;
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Order model.
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
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionAddOrder()
    {   

        $model = new Order;
        // if ($model->load(Yii::$app->request->post()) ) {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->user_id = 1;
                $model->address_id = 13;
                $model->goods_id = 28;
                $model->goods_number = 8;
                $model->goods_attr = 57;
                $model->order_type = 0;
                $model->shipping_id = 0;
                $model->pay_id = 1;
                $model->take_points = 1;
                $re = $model->addOrder();
                if ($re) {
                   
                    $transaction->commit();//提交事务

                }else{
                    echo $model->error_msg;
                }
                
               
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        // }
       
    }
    /*订单处理*/
    public function actionHandle()
    {
        $common = Yii::$app->request->post('common');
        $id = Yii::$app->request->post('id');
        $bz = Yii::$app->request->post('bz');
        if (empty($bz)&&$common!='complete'&&$common!='delivery'&&$common!='activeOrder') {

            Yii::$app->getSession()->setFlash('error', '请填写备注');
            return $this->redirect(['view', 'id' => $id]);
        }
        switch ($common) {
            case 'payOrder':/*设为已支付*/
                $this->PayOrder($id,$bz);
                break;
            case 'noPayOrder':/*设为未支付*/
                $this->NoPayOrder($id,$bz);
                break;
            case 'cancel':/*取消订单*/
                $this->Cancel($id,$bz);
                break;
            case 'activeOrder':/*确认订单*/
                $this->ActiveOrder($id,$bz);
                break;
            case 'delivery':/*发货*/
                $this->Delivery($id,$bz);
                break;
            case 'noDelivery':/*取消发货*/
                $this->NoDelivery($id,$bz);
                break;
            case 'complete':/*完成订单*/
                $this->Complete($id,$bz);
                break;
            case 'refund':/*退款*/
                $this->Refund($id,$bz);
                break;
            default:
                Yii::$app->getSession()->setFlash('error', '错误');
                return $this->redirect(['view', 'id' => $id]);
                break;
        }
    }

    public function Refund($id,$bz)
    {
       //........code
       echo  '实现';
    }

    public function ActiveOrder($id,$bz)
    {

        $order = Order::findOne($id);


        if ($order->order_status==0) {
            $order->order_status=1;
            $order->confirm_time = time();
            $order->save();
            OrderAction::add($order,$bz);/*添加操作记录*/
            Yii::$app->getSession()->setFlash('success', '操作成功！');
            $this->redirect(['view','id'=>$id]);
        }

    }
    public function PayOrder($id,$bz)
    {

        $order = Order::findOne($id);
        if ($order->pay_status==0) {
            $order->pay_status=1;
            if ($order->save()) {
                OrderAction::add($order,$bz);/*添加操作记录*/
                Yii::$app->getSession()->setFlash('success', '操作成功！');
                $this->redirect(['view','id'=>$id]);
            }else{
                Yii::$app->getSession()->setFlash('error', '操作失败！');
                $this->redirect(['view','id'=>$id]);
            }
        }

    }
    public function NoPayOrder($id,$bz)
    {

        $order = Order::findOne($id);
        if ($order->pay_status==1) {
            $order->pay_status=0;
            if ($order->save()) {

                OrderAction::add($order,$bz);/*添加操作记录*/

                Yii::$app->getSession()->setFlash('success', '操作成功！');
                $this->redirect(['view','id'=>$id]);
            }else{
                Yii::$app->getSession()->setFlash('error', '操作失败！');
                $this->redirect(['view','id'=>$id]);
            }
        }

    }
    /*完成订单*/
    public function Complete($id,$bz)
    {
        $order = Order::findOne($id);
        if ($order->order_status==1 && $order->delivery ==1 ) {
            $order->order_status=4;
            if ($order->save()) {
                $user_id = $order['user_id'];
                $order_amount = $order['order_amount'];

                OrderAction::add($order,$bz);/*添加操作记录*/

                Yii::$app->getSession()->setFlash('success', '操作成功！');
                $this->redirect(['view','id'=>$id]);
            }else{
                Yii::$app->getSession()->setFlash('error', '操作失败！');
                $this->redirect(['view','id'=>$id]);
            }
        }
    }

    /*取消订单*/
    public function Cancel($id,$bz)
    {

        $order = Order::findOne($id);
        if ($order->order_status<2) {
            $order->order_status=2;
            if ($order->save()) {
                OrderAction::add($order,$bz);/*添加操作记录*/
                Yii::$app->getSession()->setFlash('success', '操作成功！');
                $this->redirect(['view','id'=>$id]);
            }else{
                Yii::$app->getSession()->setFlash('error', '操作失败！');
                $this->redirect(['view','id'=>$id]);
            }
        }
    }
    /*发货*/
    public function Delivery($id,$bz)
    {

        $order = Order::findOne($id);
        if ($order->order_status==1 && $order->delivery ==0) {
            if ( $order->pay_id==1 && $order->pay_status==0 ) {
                Yii::$app->getSession()->setFlash('error', '操作失败！在线支付订单未支付！');
                $this->redirect(['view','id'=>$id]);
            }
            $order->delivery=1;
            if ($order->save()) {

                OrderAction::add($order,$bz);/*添加操作记录*/

                Yii::$app->getSession()->setFlash('success', '操作成功！');
                $this->redirect(['view','id'=>$id]);
            }else{
                Yii::$app->getSession()->setFlash('error', '操作失败！');
                $this->redirect(['view','id'=>$id]);
            }
        }
    }

    /*取消发货*/
    public function NoDelivery($id,$bz)
    {

        $order = Order::findOne($id);
        if ( $order->delivery ==1) {
            $order->delivery=0;
            if ($order->save()) {

                OrderAction::add($order,$bz);/*添加操作记录*/

                Yii::$app->getSession()->setFlash('success', '操作成功！');
                $this->redirect(['view','id'=>$id]);
            }else{
                Yii::$app->getSession()->setFlash('error', '操作失败！');
                $this->redirect(['view','id'=>$id]);
            }
        }
    }

    public function actionUpdatePayId($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $model->pay_name = Order::$pay_type_arr[$model->pay_id];
            if ($model->save()) {

                return null;
            }
        } else {
            return $this->renderAjax('updatePayId', [
                'model' => $model,
            ]);
        }
    }

/*导出*/
    public function actionExport()
    {   
         $data =Yii::$app->request->get();
        $objectPHPExcel = new PHPExcel();
        $searchModel = new OrderSearch();
        $searchModel->order_status = 0;
        $dataProvider = $searchModel->search($data);
        $where = $dataProvider->query->where;
        $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $titles = ['订单号','所属会员','收货人姓名','收货地址','联系电话','产品总金额','订单总金额','创建时间','订单状态','发货状态'];
        $list=Order::find()->joinWith(['member'])->where($where)->orderBy('id asc ')->all();
        $i=1;
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);//改变此处设置的长度数值 
        foreach ($titles as $k => $v) {
                 $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[$k].$i,$v);
        }

        foreach ($list as $k => $var) {
            $i++;
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[0].$i,$var['order_no'].' ');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[1].$i,Member::getName($var['user_id']));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[2].$i,$var['name']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[3].$i,$var['address']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[4].$i,$var['tel'].' ');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[5].$i,$var['goods_amount']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[6].$i,$var['order_amount']);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[7].$i,date('Y-m-d h:i:s',$var['create_time']));
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[8].$i,Order::$status_arr[$var['order_status']]);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[9].$i,Order::$delivery_arr[$var['delivery']]);

        }


        ob_start();
    
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.'订单表-'.date("Y年m月j日").'.xls"');

        $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');

        $objWriter->save('php://output');

    }
    //  /*导出配货标签*/
    // public function actionExportGoodsOrder()
    // {
    //     $data =Yii::$app->request->get();
    //     $objectPHPExcel = new PHPExcel();
    //     $searchModel = new OrderSearch();

    //     $searchModel->order_status = 1;
    //     $searchModel->delivery = 1;
    //     $dataProvider = $searchModel->search($data);
    //     $where = $dataProvider->query->where;

    //     $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    //     $titles = ['产品名称','购买数量','产品价格','所选规格','标识符'];
    //     $list=Order::find()->where($where)->orderBy('area_id asc ')->all();
    //     $i=1;
    //     $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);//改变此处设置的长度数值 
    //     foreach ($titles as $k => $v) {
    //              $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[$k].$i,$v);
    //     }
    //     foreach ($list as $key => $var) {
    //         $goods = OrderGoods::find()->where(['order_id'=>$var['id']])->orderBy('goods_id asc')->all();
    //         foreach ($goods as $k => $v) {
    //             $i++;

    //             $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[0].$i,$v['goods_name'].' ');
    //             $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[1].$i,$v['goods_number']);
    //             $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[2].$i,$v['goods_price']);
    //             $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[3].$i,$v['goods_attr']);
    //             $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[4].$i,$v['order_id']);

    //         }
    //     }
    //     ob_start();
    
    //     header('Content-Type : application/vnd.ms-excel');
    //     header('Content-Disposition:attachment;filename="'.'配货信息表-'.date("Y年m月j日").'.xls"');

    //     $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');

    //     $objWriter->save('php://output');
    // }

    //  /*导出订单详情*/
    // public function actionExportD()
    // {   
    //     $data =Yii::$app->request->get();
    //     $objectPHPExcel = new PHPExcel();
    //     $searchModel = new OrderSearch();
    //     $searchModel->order_status = 1;
    //     $searchModel->delivery = 1;
    //     $dataProvider = $searchModel->search($data);
    //     $where = $dataProvider->query->where;

    //     $letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    //     $titles = ['订单号','收货人','收货地址','联系电话','订单总金额','标识符','送货员','实收金额'];
    //     $list=Order::find()->where($where)->orderBy('area_id asc ')->asArray()->all();
    //     $i=1;
    //     $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);//改变此处设置的长度数值 
    //     $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);//改变此处设置的长度数值 

        

    //     foreach ($titles as $k => $v) {
    //              $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[$k].$i,$v);
    //     }

    //     foreach ($list as $k => $var) {
    //         $i++;
            
    //         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[0].$i,$var['order_no'].' ');
    //         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[1].$i,$var['name']);
    //         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[2].$i,$var['address']);
    //         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[3].$i,$var['tel']);


    //         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[4].$i,$var['order_amount']);
    //         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[5].$i,$var['id']);
    //         $goods_list = OrderGoods::find()->where(['order_id'=>$var['id']])->asArray()->all();
       
    //         foreach ($goods_list as $k => $v) {
    //              $i++;   
    //              $objectPHPExcel->getActiveSheet()->mergeCells('A'.$i.':K'.$i);

    //              $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($letters[0].$i,$v['goods_name'].' 数量：'.$v['goods_number'].'规格：'.$v['goods_attr']);  
    //         }
    //         $i++;
    //         $objectPHPExcel->getActiveSheet()->mergeCells('A'.$i.':K'.$i);
    //     }

      
    //     ob_start();
    
    //     header('Content-Type : application/vnd.ms-excel');
    //     header('Content-Disposition:attachment;filename="'.'发货详情表-'.date("Y年m月j日").'.xls"');

    //     $objWriter= PHPExcel_IOFactory::createWriter($objectPHPExcel,'Excel5');

    //     $objWriter->save('php://output');

    // }
    //批量确认订单
    public function actionRefuse(){
        $ids=yii::$app->request->post('id');
        $model =Order::find()->where(['in','id',$ids])->andwhere(['order_status'=>0])->all();
        if($model){
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                foreach ($model as $key => $v) {
                    $v->order_status =1;
                    if(!$v->update()){
                        throw new \Exception("确认失败");
                    }
                }
                $transaction->commit();/*提交事物*/
                return tools::jsonSuccess('确认成功');
            } catch (\Exception $e) {
                $transaction->rollback();
                return tools::jsonError($e->getMessage());
            }
        }
    }

    // /*批量发货*/
    // public function actionBatchDelivery()
    // {
    //     $data =Yii::$app->request->post();

    //     $searchModel = new OrderSearch();
    //     $dataProvider = $searchModel->search($data);
    //     $where = $dataProvider->query->where;

    //     unset($where[1]);
    //     $resulf=Order::updateAll(('and',['delivery'=>1],$where);
    //     if ($resulf) {
    //         return tools::jsonSuccess('发货成功');
    //     }else{
    //          return tools::jsonError('没有符合条件的订单');
    //     }

    // }

}
