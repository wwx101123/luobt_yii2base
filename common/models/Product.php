<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%product}}".
 *
 * @property integer $id
 * @property string $goods_name
 * @property string $goods_code
 * @property integer $cate_id
 * @property string $goods_unit
 * @property integer $brand_id
 * @property string $goods_weight
 * @property string $goods_img
 * @property integer $inventory
 * @property string $content
 * @property string $market_price
 * @property string $present_price
 * @property integer $is_show
 * @property integer $is_top
 * @property integer $is_hot
 * @property integer $is_new
 * @property integer $is_reg
 * @property integer $create_time
 */
class Product extends \yii\db\ActiveRecord
{
    const IS_SHOW=1;
    const IS_REG=1;
    const NO_NUMBER = '库存不足';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_name', 'goods_code','goods_img', 'inventory', 'content', 'market_price', 'present_price', 'is_show', 'is_top', 'is_hot', 'is_new', 'is_reg', 'create_time','is_login'], 'required'],
            [['cate_id', 'inventory', 'is_show', 'is_top', 'is_hot', 'is_new', 'is_reg', 'create_time','big_id','is_login'], 'integer'],
            [['content'], 'string'],
            [['market_price', 'present_price','account3_price','account5_price'], 'number'],
            [['goods_name', 'goods_code'], 'string', 'max' => 155],
            [['goods_img'], 'string', 'max' => 255],
            [['goods_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'goods_name' => Yii::t('app', '商品名称'),
            'goods_code' => Yii::t('app', '商品编码'),
            'big_id' =>Yii::t('app', '商品类别'),
            'cate_id' => Yii::t('app', '分类Id'),
            // 'goods_unit' => Yii::t('app', '单位'),
            'goods_img' => Yii::t('app', '商品图片'),
            'inventory' => Yii::t('app', '库存'),
            'content' => Yii::t('app', '简介'),
            'market_price' => Yii::t('app', '市场价格'),
            'present_price' => Yii::t('app', '消费积分'),
            'is_show' => Yii::t('app', '是否显示'),
            'is_top' => Yii::t('app', '是否置顶'),
            'is_hot' => Yii::t('app', '是否热门'),
            'is_new' => Yii::t('app', '是否新品'),
            'is_reg' => Yii::t('app', '是否促销'),
            'create_time' => Yii::t('app', '创建时间'),
            'is_login' => Yii::t('app', '是否注册产品'),
            'account3_price'=>Yii::t('app','消费积分'),
            'account5_price'=>Yii::t('app','股权积分'),

        ];
    }

    public static function randProduct()
    {
        $arr= self::find()->where(['is_show'=>self::IS_SHOW,'is_reg'=>self::IS_REG])->select('id,goods_name,goods_img,present_price')->asArray()->all();
       $result = self::RequestList($arr,5);
       return $result ?:[];
    }

    public static function RequestList($array, $i)
    {
        if ($array) {
            if(count($array)<=$i){
                return $array;
            }else{
                $rand=array_rand($array,$i);
                    foreach($rand as $val){
                        $data_last[]=$array[$val];
                    }
                return $data_last;
            }
        }
    }
       public static function GetName($goods_id)
    {
        $data = self::findOne($goods_id);
        if ($data) {
            
            return $data->goods_name;
        }
    }
    /*获取产品已有属性*/
    public static function getGoodsHasAttr($goods_id)
    {
        $query =  GoodsAttr::find()->joinWith('attrInfo',false)->where(['attr_type'=>1,'ld_attr_info.goods_id'=>$goods_id]);
        // echo $query->createCommand()->getRawSql();
        return $query->asArray()->all();
    }
    /*获取产品已有属性值*/
    public static function getGoodsHasAttrInfo($goods_id,$attr_id)
    {
        $query =  AttrInfo::find()->joinWith('goodsAttr',false)->where(['attr_id'=>$attr_id,'goods_id'=>$goods_id]);
        // echo $query->createCommand()->getRawSql();
        return $query->asArray()->all();
    }

    /*获取产品库存*/
    public  function getNumber()
    {
        return $this->getProductNumber();
    }
    /*货品库存（有规格）*/
    public function getGoodsNumber()
    {
        $data=Goods::findGoodsByAttr($this->goods_id,$this->attr_id);

        if ($data) {
            if ($data['inventory']<$this->inventory) {

                $this->error_msg = self::GetName($this->goods_id).self::NO_NUMBER;
                return false;
            }else{

                return $this->inventory;
            }
        }else{

            $this->error_msg = self::NO_ATTR;
            return false;
        }
    }
    /*产品价格（无规格）*/
    public function getProductNumber()
    {
        $data =  self::find()->where(['id'=>$this->id])->select('present_price,inventory')->asArray()->One();
            if ($data) {
              
                if ($data['inventory']<$this->inventory) {

                    throw new \Exception(self::GetName($this->id).self::NO_NUMBER);
                   
                }else{
                    return $this->inventory;
                }
            }else{
                throw new \Exception(self::NO_PRODUCT);
        }
    }

    public function getAttrInfo()
    {
        return $this->hasMany(AttrInfo::className(),['goods_id'=>'goods_id']);
    }

    public function getGoods()
    {
        return $this->hasMany(Goods::className(),['goods_id'=>'goods_id']);
        // ->viaTable('ld_attr_info', ['goods_id' =>'goods_id']);;
    }
    public function getGoodsAttrInfo()
    {
        return $this->hasMany(Goods::className(),['goods_id'=>'goods_id'])
        ->via('attrInfo');;
    }

    public function beforeDelete()  
    {   
         // Goods::deleteAll(['goods_id'=>$this->goods_id]);
        ShopCar::deleteAll(['goods_id'=>$this->id]);
        return parent::beforeDelete();  
    }  

   
    /*出库*/
    public function placing()
    {   
        $pro = self::findOne($this->id);
        $pro->updateCounters(['inventory'=>-$this->inventory]);
        if($pro){
            return true;
        }else{
            return false;
        }
    }
    /*入库*/
    public function storage()
    {
        $pro = self::findOne($this->goods_id);
        $pro->updateCounters(['inventory'=>$this->inventory]);
        if ($this->attr_id) {
       
            $data=Goods::findGoodsByAttr($this->goods_id,$this->attr_id);
            if ($data) {
                return $data->updateCounters(['inventory'=>$this->inventory]);
            }else{
                $this->error_msg ='系统繁忙，请重试';
                return false;
            }
        }else{
            return true;
        }
    }
    /*获取报单产品*/
    
    public static function getRegGoods($tp,$le)
    {
        $cpzj = Parameter::getCpzjByLevel(0);
        if ($tp==1) {
         $list = self::find()->where(['is_login'=>1,'present_price'=>$cpzj])->orderBy('id desc')->limit(4)->asArray()->all();
        }
        return yii\helpers\ArrayHelper::map($list,'id','goods_name');
    }

    public static function getImg($goods_id)
    {
        $goods = self::find()->where(['id'=>$goods_id])->asArray()->select('goods_img')->one();
        if ($goods) {
            return $goods['goods_img'];
        }
    }
    public static function getPrice($goods_id)
    {
        $goods = self::find()->where(['id'=>$goods_id])->asArray()->select('present_price')->one();
        if ($goods) {
            return $goods['present_price'];
        }
    }


    public static function getGoodsList($tp,$le)
    {
        $cpzj = Parameter::getCpzjByLevel($le);
        if ($tp==1) {
        $list = self::find()->select(['id','goods_name','goods_img','present_price'])->where(['is_login'=>1,'present_price'=>$cpzj])->orderBy('id desc')->limit(4)->asArray()->all();
        }
        return $list;
    }

    //获取到相应分类下面的产品
    public static function getBigGoods($id){
        $model =self::find()->where(['big_id'=>$id])->asArray()->all();
        if($model){
            return $model;
        }
    } 
    public static function getProductList(){
        $goods = Product::find()->select('id, goods_name, goods_img, present_price')->where(['is_show' => Product::IS_SHOW, 'is_login' => 1])->asarray()->orderBy(['id' => SORT_DESC])->all();
       $data = ArrayHelper::map($goods, 'id', 'goods_name');
       return $data;
    }
}

