<?php

namespace frontend\controllers;

use yii;
use common\models\ShopCar;
use common\models\Product;
use common\models\AttrInfo;
use common\helps\tools;

class ShopCarController extends LdBaseController
{


    public function actionAddShopCar()
    {
        $goods_id = Yii::$app->request->get('goods_id');
        $buy_num  = Yii::$app->request->get('buy_num');
        $type  = Yii::$app->request->get('add_type')?Yii::$app->request->get('add_type'):0;
        $user_id = yii::$app->user->id;
        $pro_data = Product::find()->where(['id'=>$goods_id])->asArray()->one();
        $market_price = $pro_data['market_price'];
        $present_price = $pro_data['present_price'];
        $model = new ShopCar;
       
        $model->type = $type;
        $model->goods_id = $goods_id;
        $model->user_id = $user_id;
        $model->goods_num = $buy_num;
        $model->goods_name = $pro_data['goods_name'];
        $model->market_price = $market_price;
        $model->present_price = $present_price;
        //$model->account3_price = $pro_data['account3_price'];
        //$model->account5_price = $pro_data['account5_price'];
        $model->goods_img = $pro_data['goods_img'];
        try {
            $result= $model->addShopCar();//判断是新增记录 还是购买数量叠加
            return $this->renderJsonSuccess('添加成功',$result);
        } catch (\Exception $e) {
            return $this->renderJsonError($e->getMessage());;
        }   

    }

    public function actionCar()
    {   
        $model = new ShopCar;
        $model->user_id = yii::$app->user->id;
        $model->type = 0;
        $amount = $model->getAmount();
        $count = $model->getCarCount();
        $list = $model->getMyCar();
        // $account7_sum= $model->getPresentSum();
        // $account3_sum= $model->getAccount3Sum();
        // $account5_sum= $model->getAccount5Sum();
        return $this->render('car', [
            'list'=>$list,
            'amount'=>$amount,
            'count'=>$count,
            // 'account7_sum'=>$account7_sum,
            // 'account3_sum'=>$account3_sum,
            // 'account5_sum'=>$account5_sum,
        ]);
    }

    public function actionGetCar()
    {   
        $type  = Yii::$app->request->get('type')?Yii::$app->request->post('type'):0;
        $model = new ShopCar;
        $model->user_id = $this->_UserID;
        $model->type = $type;
        $list = $model->getMyCar();
        return tools::jsonSuccess('成功',['data'=>$list]);
    }

    public function actionGetCarNum()
    {   
        $type  = Yii::$app->request->get('type')?Yii::$app->request->get('type'):0;
        $user_id = $this->_UserID;
        $num = ShopCar::find()->where(['user_id'=>$user_id,'type'=>$type])->sum('goods_num');
        if ($num) {
            echo $num;
        }else{
            echo 0;
        }
        
    }

    public function actionUpdateNum()
    {
      $id = Yii::$app->request->get('id');
        $type = Yii::$app->request->get('type');
        $buy_type = Yii::$app->request->get('buy_type')?Yii::$app->request->get('buy_type'):0;
        if ($type==0) {
            $num = 1;
        }else{
            $num = -1;
        }

        $sc = ShopCar::find()->where(['id'=>$id])->one();
        
        $sc->goods_num += $num;

        $model = new Product;
        $model->goods_id = $sc->goods_id;
        $model->attr_id = $sc->attr_id;
        $model->goods_number = $sc->goods_num;
        $result = $model->getNumber();
        if ($result||$type==1||$buy_type==1) {
            if ($sc->goods_num>0) {
                $sc->save(false);
            }else{
                $sc->delete();
            }
            return tools::jsonSuccess('添加成功',['goods_num'=>$sc->goods_num,'id'=>$sc->id,'goods_id'=>$sc->goods_id]);  
        }else{
            return tools::jsonError('库存不足');
        }
    }

    public function actionChangeCar()
    {
        $id = Yii::$app->request->get('id');
        $type = Yii::$app->request->get('type');
        if ($type==0) {
            $num = 1;
        }else{
            $num = -1;
        }
        $shop_car = ShopCar::find()->where(['goods_id'=>$id,'type'=>1]);

        if ($type==1) {
            $count=$shop_car->count();
            if ($count>1) {
                return tools::jsonError('多规格商品只能去购物车删除哦');
                exit;
            }
        }
        $sc = $shop_car->one();
       
        $sc->goods_num += $num;
        if ($sc->goods_num>0) {
            $sc->save(false);
        }else{
            $sc->delete();
        }

        return tools::jsonSuccess('添加成功',['goods_num'=>$sc->goods_num,'id'=>$sc->id,'shop_price'=>$sc->shop_price]);

    }


    public function actionGetAmount()
    {
        $id = Yii::$app->request->get('id');
        $ids = explode(',', $id);
        $sc=ShopCar::find()->where(['in','id',$ids]);
        $amount = $sc->sum('shop_price * goods_num');
        $count = $sc->count;

        
    }

    public function actionGetIds()
    {
        $shop_car = ShopCar::find()->select(['id'])->where(['type'=>1])->asArray()->all();
        $ids = '';
        foreach ($shop_car as $k => $v) {
            if ($ids=='') {
                $ids = $v['id'];
            }else{
                $ids .= ','.$v['id'];
            }
        }
        if ($ids) {
            return tools::jsonSuccess('请求成功',['ids'=>$ids]);
        }else{
            return tools::jsonError('请添加商品');
        }
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');
        $rs= ShopCar::deleteAll('id = :id and user_id = :UserID',[':id'=>$id,':UserID'=>yii::$app->user->id]);
        if ($rs) {
            return $this->renderJsonSuccess('删除成功！');
        }else{
            return $this->renderJsonError('删除失败！');
        }
    }

    public function actionDelShopCar()
    {
        $rs= ShopCar::deleteAll('user_id = :UserID',[':UserID'=>yii::$app->user->id]);
        if ($rs) {
            return $this->renderJsonSuccess('清空成功');
        }else{
            return $this->renderJsonError('购物车已经清空');
        }
    }
}
