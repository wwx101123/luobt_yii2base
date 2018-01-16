<?php

namespace frontend\controllers;
use yii;
use common\models\ShopCar;
use common\models\Address;
use common\models\Order;
use common\models\Params;
use common\models\Member;
use common\models\Base;
use frontend\models\ValidatePassword;
use common\bonus\XfBonus;
use yii\data\Pagination;
use common\models\Account;
use common\models\OrderAction;

class OrderController extends LdBaseController
{
    public function actionIndex()
    {   
        $query = Order::find()->where(['user_id'=>Yii::$app->user->getId()]);
        $pages = new Pagination([
            'defaultPageSize' => 8,
            'totalCount' => $query->count(),
        ]);
        $list = $query->orderBy('id DESC')->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index',[
            'list' => $list,
            'pages' => $pages,
            //'id' => $orderId;
        ]);
    }

    /*确认订单*/
    public function actionConfirmOrder()
    {   
        $id = Yii::$app->request->get('id')?Yii::$app->request->get('id') : 0;
        $ids = explode(',', $id);

        $shopCar = new ShopCar;
        if ($id) {
            $shopCar->ids = $ids;
        }
        $shopCar->user_id = yii::$app->user->id;
        $shopCar->type = 0;
        $sc = $shopCar->getMyShopCar();
        $count = $sc->count();
        $amount = $shopCar->getAmount();
        $list = $sc->all();/*登录会员的购物车列表*/
      
        $user = Member::find()->where(['id'=>yii::$app->user->id])->one();
        /*收货地址*/
        $model= new Address;
        $model->user_id = yii::$app->user->id;
        $address = $model->getAddressList();//获取登录会员的收货地址信息
        $account = Account::find()->where('member_id = :mid', [':mid' => $this->user->id])->one();
        if (!$list) {
            return $this->render('shop-null');
        }
        // $account7_sum= $shopCar->getPresentSum();
        // $account3_sum= $shopCar->getAccount3Sum();
        // $account5_sum= $shopCar->getAccount5Sum();
        return $this->render('confirmOrder', [   
            'count'=>$count,
            'list'=>$list,
            'amount'=>$amount,
            'address'=>$address,
            'id'=>$id,
            'user'=>$user,
            'account' => $account,
            // 'account7_sum'=>$account7_sum,
            // 'account3_sum'=>$account3_sum,
            // 'account5_sum'=>$account5_sum,        
        ]);
    }

    // 加入订单
    public function actionAddOrder()
    {   
         if(Yii::$app->request->post()){
            $data =Yii::$app->request->post();
            $ids = $data['id'];
            $model = new Order;
            $model->attributes = $data;
            $model->address_id = isset($data['address_id']) ? $data['address_id'] : 0;
            $model->user_id = yii::$app->user->id;
            $model->ids = $ids;
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $re = $model->addOrder();
                if ($re) {
                   // if (!Account::gouwu($model->order_amount, $this->user->id)) {
                   //      throw new \Exception("余额不足", 1);    
                   //  }
                    ShopCar::deleteAll(['and',['in','id',$ids],['user_id' => yii::$app->user->id]]);
                    $transaction->commit();
                    return $this->renderJsonSuccess('购买成功',['id' => $re]);
                } else {
                    return $this->renderJsonError($model->error_msg);
                }
             } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->renderJsonError($e->getMessage());
            }

        }
    }

    /*获取订单列表*/
    public function actionGetOrderList()
    {   
        $x = Yii::$app->request->get('x')?Yii::$app->request->get('x'):0;
        $type = Yii::$app->request->get('type')?Yii::$app->request->get('type'):0;
        $limit = 10;
        $offset = $x * $limit;
        $model=Order::find()->where(['user_id'=>$this->_UserID]);
        if ($type==1) {
            $model->andWhere(['order_status'=>0]);
        }
        if ($type==2) {
            $model->andWhere(['order_status'=>1,'pay_status'=>0]);
        }
        if ($type==3) {
            $model->andWhere(['order_status'=>4]);
        }
        if ($type==4) {
            $model->andWhere(['order_status'=>3]);
        }
        $list = $model->With('orderGoods')->orderBy('id desc')->limit($limit)->offset($offset)->asArray()->all();
        // echo "<pre>";
        if ($list) {
            return tools::jsonSuccess('成功',['list'=>$list]);
        }else{
            return tools::jsonError('没有了');;
        }
    }

    /*订单详情*/
    public function actionOrderView()
    {
        $this->layout = 'column1';
        $id = Yii::$app->request->get('id');
        $data = Order::find()->where(['id'=>$id])->asArray()->With('orderGoods')->one();
        $status = Order::$status_arr[$data['order_status']];
        $wechat = Yii::$app->wechat;
        return $this->render('orderView',['data'=>$data,'wechat'=>$wechat]);
    }

    public function actionGetAmount()
    {
        $type = Yii::$app->request->post('type');
        $goods_id = Yii::$app->request->post('goods_id');
        $ids = explode(',', $goods_id);
        $shopCar = new ShopCar;
        $shopCar->ids = $ids;
        $amount = $shopCar->getAmount();

        if ($type==1) {
            $user = UserWeixin::find()->where(['id'=>$this->_UserID])->one();
            $score = $user->score;

            $score_arr =Order::getUseScore($amount,$score);/*可用积分*/


            $amount-=$score_arr['use_money'];
        }
        return tools::jsonSuccess('成功',['amount'=>$amount]);
    }
    public function actionCancel()
    {
        $id = Yii::$app->request->post('id');
        $order = Order::findOne($id);
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            if ($order->Cancel()) {
            
                UserWeixin::setScore($order->user_id,$order->use_score,'取消订单积分返还',1);
                $transaction->commit();
                return tools::jsonSuccess('订单取消成功');
            }else{
                return tools::jsonError($order->error_msg);
            }
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        
    }

    public function actionOrderDone()
    {
        return $this->render('order-done');
    }

    /**
     * 订单签收
     * @return void
     * @author luobt17
     **/
    public function actionCompleteOrder($id)
    {
        $order = Order::findOne($id);
        if ($order->order_status == 1 && $order->delivery == 1) {
            $order->order_status = 4;
            if ($order->save()) {
                $user_id = $order['user_id'];
                $order_amount = $order['order_amount'];
                //echo Yii::$app->getSession()->setFlash('success', '操作成功！');
                if (OrderAction::add($order, $bz = null)) {
                    Yii::$app->session->setFlash('info', '签收完成!');
                    return $this->redirect(['index']);
                }     
            } else {
                Yii::$app->sessiohn->setFlash('error', '签收！');
                $this->redirect(['index', 'id'=>$id]);
            }
        }
    }

}
