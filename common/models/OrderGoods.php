<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order_goods}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property string $goods_name
 * @property string $goods_sn
 * @property integer $buy_number
 * @property string $market_price
 * @property string $present_price
 * @property string $goods_attr
 * @property string $goods_img
 */
class OrderGoods extends \yii\db\ActiveRecord
{   
    public $shop_ids;
    public $error_msg;

    const SAVE_FAIL = '保存失败';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_goods}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['order_id', 'goods_id', 'goods_name', 'goods_sn', 'buy_number', 'market_price', 'present_price', 'goods_attr', 'goods_img'], 'required'],
            [['order_id', 'goods_id', 'buy_number'], 'integer'],
            [['market_price', 'present_price','account3_price','account5_price'], 'number'],
            [['goods_name'], 'string', 'max' => 255],
            [['goods_img'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'goods_id' => Yii::t('app', 'Goods ID'),
            'goods_name' => Yii::t('app', '产品名称'),
            'buy_number' => Yii::t('app', '购买数量'),
            'market_price' => Yii::t('app', '市场价格'),
            'present_price' => Yii::t('app', '销售价'),
            'goods_img' => Yii::t('app', '产品图片'),
            'account3_price' =>Yii::t('app', '消费积分'),
            'account5_price' => Yii::t('app', '股权积分'),
        ];
    }
    /*加入产品订单表*/
    public function addOrderGoods()
    {   
        $data = Product::findOne($this->goods_id);
        $model = new self;
        $model->order_id = $this->order_id;
        $model->goods_id = $this->goods_id;
        $model->buy_number = $this->buy_number;
        $model->goods_name = $data->goods_name;
        $model->market_price = $data->market_price;
        $model->present_price = $data->shop_price;
        $model->goods_img = $data->original_img;
        if ($this->goods_attr) {
            $attr = AttrInfo::find()->where(['id'=>$this->goods_attr])->asArray()->One();

            if ($attr) {
                $model->goods_attr = $attr['attr_value'];
                $model->present_price = $attr['attr_price'];
                $model->market_price = $attr['attr_price'];
            }
        }
        if ($model->save(false)) {
            return true;
        }else{
            $this->error_msg  = self::SAVE_FAIL;
            return false;
        }
    }

     public function setOrderAmount()
    {
        $goods =  OrderGoods::find()->where(['order_id'=>$this->order_id])->asArray()->all();
        $amount = 0;
        foreach ($goods as $k => $v) {
            $amount += $v['present_price'] * $v['buy_number'];
        }
        $order = Order::findOne($this->order_id);
        $order->goods_amount = $amount;
        $order->order_amount = $order->shipping_fee + $amount;
        if ($order->save(false)) {
            return true; 
        }else{
            return false;
        }

    }
}
