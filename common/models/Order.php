<?php

namespace common\models;

use Yii;


/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property string $order_no
 * @property integer $user_id
 * @property integer $order_status
 * @property integer $shipping_status
 * @property integer $pay_status
 * @property string $name
 * @property string $address
 * @property string $postcode
 * @property string $tel
 * @property integer $shipping_id
 * @property string $shipping_name
 * @property integer $pay_id
 * @property string $pay_name
 * @property string $goods_amount
 * @property string $shipping_fee
 * @property string $order_amount
 * @property string $money_paid
 * @property integer $create_time
 * @property integer $confirm_time
 * @property string $goods_img
 */
class Order extends \yii\db\ActiveRecord
{   


    const NO_ADDRESS =  '请选择收货地址' ;
    const NO_SAVE =  '订单提交失败，请重试' ;
    const ORDER_ERROR =  '255' ;
    const ORDER_REG =  '1' ;
    const ORDER_UP= '2' ;

    /*订单商品*/
    public $goods_id ;
    /*购买商品数量*/
    public $goods_number ;
    /*商品属性*/
    public $goods_attr ; 
     /*商品属性*/
    public $score_type ;

     /*收获地址ID*/
    public $address_id ;
    /*错误属性*/
    public $error_msg ;

    public $usename;
    public $ids='';


    public static $status_arr = array(null=>'请选择','未确认','确认','已取消','已退款','已完成');
    public static $pay_arr = array(null=>'请选择','未付款','已付款');
    public static $delivery_arr = array(null=>'请选择','待发货','已发货');
    public static $shipping_arr = array(null=>'请选择','提货点自提','送货上门');
    public static $pay_type_arr = array(null=>'请选择','在线支付','货到付款');
    public static $order_types = ['0'=>'商城订单','1'=>'注册订单','2'=>'升级订单'];
    public static $types = [null=>'请选择','1'=>'注册订单','2'=>'升级订单'];
    public static $status_array = array(null=>'请选择',0=>'未确认',1=>'确认',4=>'已完成');

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['order_no', 'user_id', 'order_status', 'shipping_status', 'pay_status', 'name', 'address', 'postcode', 'tel', 'shipping_id', 'shipping_name', 'pay_id', 'pay_name', 'goods_amount', 'shipping_fee', 'order_amount', 'money_paid', 'create_time', 'confirm_time', 'goods_img'], 'required'],
            [['user_id', 'order_status', 'shipping_status', 'pay_status', 'shipping_id','area_id', 'pay_id', 'confirm_time','create_time','delivery'], 'integer'],
            [['goods_amount','order_amount'], 'number'],
            [['order_no', 'postcode'], 'string', 'max' => 30],
            [['name','usename'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 255],
            [['pay_name'], 'string', 'max' => 120],
            [['tel'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_no' => Yii::t('app', '订单号'),
            'user_id' => Yii::t('app', '所属会员'),
            'order_status' => Yii::t('app', '订单状态'),
            'shipping_status' => Yii::t('app', 'Shipping Status'), //配送状态
            'pay_status' => Yii::t('app', '支付状态'),
            'name' => Yii::t('app', '收货人姓名'),
            'address' => Yii::t('app', '收货地址'),
            'postcode' => Yii::t('app', '邮政编码'),
            'tel' => Yii::t('app', '联系电话'),
            'shipping_id' => Yii::t('app', '配送方式'),
            'shipping_name' => Yii::t('app', '配送方式'),
            'pay_id' => Yii::t('app', '支付方式'),
            'pay_name' => Yii::t('app', '支付名称'),
            'goods_amount' => Yii::t('app', '产品总金额'),
            'order_amount' => Yii::t('app', '订单总金额'),
            'create_time' => Yii::t('app', '创建时间'),
            'confirm_time' => Yii::t('app', 'Confirm Time'),
            // 'goods_img' => Yii::t('app', '产品图片'),
            's_date' => Yii::t('app', '开始时间'),
            'e_date' => Yii::t('app', '结束时间'),
            'delivery' => Yii::t('app', '发货状态'),
            'order_type' => Yii::t('app', '订单类型'),
        ];
    }


    public function getMember()
    {  
        return $this->hasMany(Member::className(),['id'=>'user_id']);
    }  


    public function getOrderGoods()
    {
        return $this->hasMany(OrderGoods::className(),['order_id'=>'id']);
    }



    public function addOrder()
    {
        $this->order_no = $this->getGoodsNo();/*订单号*/
        $address = Address::getMyAddress($this->address_id,$this->user_id);
        if ($address) {
            $this->attributes = $address;/* 收货地址赋值*/
        } else {
            throw new \Exception(self::NO_ADDRESS);
        }
        $shopCar = new ShopCar;
        $shopCar->ids = $this->ids;
        $shopCar->user_id = $this->user_id;
        $this->goods_amount  = $shopCar->getAmount();/*产品金额*/
        if ($this->goods_amount <= 0) {
            throw new \Exception('订单已提交');
        }
        $this->order_amount = $this->goods_amount;/*订单总金额*/
        $this->create_time = time();
        $this->order_status = 0;/*订单确认状态*/
        $this->confirm_time = time();
        $this->area_id = 0;
        $this->postcode = 0;
        if ($this->save(false)) {
                $sc = $shopCar->getMyShopCar()->all();
                foreach ($sc as $key => $var) {
                    $reslut = false;       /*保存订单产品情况*/
                    $placing_result = true;/*出库情况*/
                    $product = new Product;
                    $product->id = $var['goods_id'];
                    $product->inventory = $var['goods_num'];
                    $pro_result = $product->getNumber();
                    if($pro_result){
                        $placing_result= $product->placing();/*出库*/
                    }
                    if ($pro_result) {
                        $order_goods = new OrderGoods;
                        $order_goods->order_id = $this->id;
                        $order_goods->goods_id = $var['goods_id'];
                        $order_goods->goods_name = $var['goods_name'];
                        $order_goods->buy_number = $var['goods_num'];
                        $order_goods->market_price = $var['market_price'];
                        $order_goods->present_price = $var['present_price'];
                        // $order_goods->account3_price = $var['account3_price'];
                        // $order_goods->account5_price = $var['account5_price'];
                        $order_goods->goods_img = $var['goods_img'];
                        $order_goods->create_time = time();
                        $reslut = $order_goods->save();
                        if (!$reslut || !$placing_result) {
                            break;
                        }
                    }else{
                         throw new \Exception($product->error_msg);  
                    }
                }
                if ($reslut) {
                    return $this->id;
                }else{
                     throw new \Exception(self::NO_SAVE);
                   
                }
        }else{
            throw new \Exception(self::NO_SAVE);
        }

    }

    /*订单号 年月日时分+会员id + 流水号*/
    public function getGoodsNo()
    {   
        // $data = Base::find()->asArray()->One();
        // $day_order = $data['day_order'];
        return date('ymdhi').$this->user_id.rand(10,100);
    }
    
   //  /*获取产品总金额*/
   //  public function getGoodsAmount()
   //  {
   //      $shopCar = new ShopCar;
   //      $shopCar->user_id = $this->user_id;
   //      $amount = $shopCar->getAmount();

   //      if ($amount) {
   //          return $amount;
   //      }else{

   //          throw new \Exception('订单已提交');
   //      }
        
   //  }
   //  /*取消订单*/
   // public function Cancel()
   // {
   //      $this->order_status = 2;
   //      if ($this->update()) {
   //          $goods_list = OrderGoods::find()->where(['order_id'=>$this->id])->all();
   //          foreach ($goods_list as $k => $v) {
   //              $product = new Product;
   //              $product->goods_id = $v['goods_id'];
   //              $product->goods_number = $v['goods_number'];
   //              $product->attr_id  = $v['attr_id'];
   //              if (!$product->storage()) {
   //                  $this->error_msg = $product->error_msg;
   //                  break;
   //              };

   //          }
   //          return true;
   //      }else{
   //          $this->error_msg = '系统繁忙，请重试';
   //      }
   // }
    // public static function getStatus($order_id)
    // {
    //     $order=self::find()->where(['id'=>$order_id])->one();
    //     if ($order) {
    //         $order_status = $order['order_status'];
    //         $pay_id = $order['pay_id'];
    //         $pay_status = $order['pay_status'];
    //         $delivery = $order['delivery'];/*发货状态*/
    //         if ($order_status!=1) {
    //             return self::$status_arr[$order_status];
    //         }else {
    //             if ($pay_status != 1 && $pay_id == 0) {
    //                 return self::$pay_arr[$pay_status];
    //             }else{
    //                 return self::$delivery_arr[$delivery];
    //             }
    //         }
    //     }
    // }

    /** 保存之前 */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $shopCar = new ShopCar;
                $shopCar->ids = $this->ids;
                $shopCar->user_id = $this->user_id;
                
                // $getPresentSum = $shopCar->getPresentSum();
                // $getAccount3Sum  = $shopCar->getAccount3Sum();
                // $getAccount5Sum = $shopCar->getAccount5Sum();
                // if($getPresentSum > 0){
                //     $rs_7 = Account::updateAllCounters(['account7'=>-$getPresentSum], ['and',['member_id'=>$this->user_id],['>=', 'account7', $getPresentSum]]);
                //     if (!$rs_7) {
                //         throw new \Exception('余额不足');
                //     }
                // }
                // if($getAccount3Sum>0){
                //     $rs_3= Account::updateAllCounters(['account3'=>-$getAccount3Sum], ['and',['member_id'=>$this->user_id],['>=', 'account3', $getAccount3Sum]]);
                //     if (!$rs_3) {
                //         throw new \Exception('余额不足');
                //     }
                // }
                $getAmount = $shopCar->getAmount();
                if($getAmount > 0){
                    // $result = Account::updateAllCounters(['account5' => -$getAmount], ['and',['member_id'=>$this->user_id],['>=', 'account5', $getAmount]]);
                    // if (!$result) {
                    //     throw new \Exception('余额不足');
                    // }
                    Account::addAccount($this->user_id, -$getAmount, 'account5', '购物');
                }
                // $getAmount = $shopCar->getAmount();
                // if  ($getAmount > 0) {
                //     $result = Account::updateAllCounters(['account4' => -$getAmount], [
                //         '>=', ['member_id' => $this->user_id],
                //         'account4',
                //         $getAmount,
                //     ]);
                //     if ($result) {
                //         throw new Exception("余额不足123");
                //     }
                // } 
            }
            return true;
            
        } else {
            return false;
        }
    }

   
}
