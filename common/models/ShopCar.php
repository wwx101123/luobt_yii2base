<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%shop_car}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $goods_id
 * @property integer $goods_num
 * @property string $goods_name
 * @property string $market_price
 * @property string $present_price
 * @property string $goods_img
 * @property integer $create_time
 */
class ShopCar extends \yii\db\ActiveRecord
{   
    //用来接收购物车选择过来的idS
    public $ids='';
    public $add_type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shop_car}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'goods_id', 'goods_num', 'goods_name', 'market_price', 'present_price', 'goods_img', 'create_time',], 'required'],
            [['user_id', 'goods_id', 'goods_num', 'create_time','type'], 'integer'],
            [['market_price', 'present_price','account3_price','account5_price'], 'number'],
            [['goods_name', 'goods_img'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', '用户名称'),
            'goods_id' => Yii::t('app', '商品id'),
            'goods_num' => Yii::t('app', '购买数量'),
            'goods_name' => Yii::t('app', '商品名称'),
            'market_price' => Yii::t('app', '市场价'),
            'present_price' => Yii::t('app', '现价'),
            'goods_img' => Yii::t('app', '配图'),
            'create_time' => Yii::t('app', '加入购物车时间'),
            'type' =>Yii::t('app', '类型'),
            'account3_price'=>Yii::t('app','消费积分'),
            'account5_price'=>Yii::t('app','股权积分'),
        ];
    }
  public function addShopCar()
    {   
        $rs = self::find()->where(['user_id'=>$this->user_id,'goods_id'=>$this->goods_id])->one();
        if ($rs) {
            $rs->goods_num += $this->goods_num;
            if ($rs->save(false) ) {
                return $rs;
            }
        }else{
            if ($this->save(false) ) {
                return $this;
            } 
        }
    }

    public function getMyShopCar()
    {   
        $sc=ShopCar::find();
        if ($this->ids) {
             $sc->where(['in','id',$this->ids])->andWhere(['user_id'=>$this->user_id]);
        }else{
            $sc->where(['user_id'=>$this->user_id]);
        }
        return $sc->asArray();

    }

    public function getAmount()
    {
        $sc=$this->getMyShopCar();
        // $amount = $sc->sum('goods_num * (present_price+account3_price+account5_price)');
        $amount = $sc->sum('goods_num * (present_price)');
        return $amount;
    }

    public function getCarCount()
    {
        $count = $this->getMyShopCar()->count();
        return $count;
    }

    public function getPresentSum()
    {
        $sc=$this->getMyShopCar();

        $amount = $sc->sum('present_price * goods_num');
        return $amount;
    }
    public function getAccount3Sum()
    {
        $sc=$this->getMyShopCar();

        $amount = $sc->sum('account3_price * goods_num');
        return $amount;
    }

    public function getAccount5Sum()
    {
        $sc=$this->getMyShopCar();

        $amount = $sc->sum('account5_price * goods_num');
        return $amount;
    }

    public function getMyCar()
    {
        return  self::find()->where(['user_id'=>yii::$app->user->id])->asArray()->all();

    }

    // public function getMyCar()
    // {
    //    $list =  self::find()->where(['user_id'=>$this->user_id])->asArray()->all();
    //    $arr = [];
    //    $amount = 0;
    //    if ($list) {
    //        foreach ($list as  $pro) {
    //             $arr['list'][$pro['merchants_id']]['goods'][] = $pro;
    //             $arr['list'][$pro['merchants_id']]['name'] = Member::getMerName($pro['merchants_id'])?Member::getMerName($pro['merchants_id']):'英雄儿女';
    //             $amount += $pro['shop_price'] * $pro['goods_num'];
    //        }
    //        $count = count($list);
    //        $arr['count']= $count;
    //        $arr['amount']= $amount;
    //    }
    //    return $arr;
    // }

    /**/
    public function getShopCarWithIds($ids)
    {
      $list =  self::find()->where(['in','id',$ids])->joinWith('product')->andWhere(['user_id'=>$this->user_id])->asArray()->all();
      $arr = [];
       foreach ($list as  $pro) {

            $arr[$pro['merchants_id']]['goods'][] = $pro;

            if (isset($arr[$pro['merchants_id']]['amount'])) {
                $arr[$pro['merchants_id']]['amount'] += $pro['shop_price'] * $pro['goods_num'];
                $arr[$pro['merchants_id']]['need_e'] += $pro['need_e'] * $pro['goods_num'];
                $arr[$pro['merchants_id']]['need_cash'] += $pro['need_cash'] * $pro['goods_num'];
            }else{
                 $arr[$pro['merchants_id']]['amount'] = $pro['shop_price'] * $pro['goods_num'];
                 $arr[$pro['merchants_id']]['need_e'] = $pro['need_e'] * $pro['goods_num'];
                 $arr[$pro['merchants_id']]['need_cash'] = $pro['need_cash'] * $pro['goods_num'];
            }
           
       }

       return $arr;
    }
    public function getProduct()
    {
        return $this->hasOne(Product::className(),['goods_id'=>'goods_id']);
    }
}
