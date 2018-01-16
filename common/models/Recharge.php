<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
/**
 * This is the model class for table "{{%recharge}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $type
 * @property string $re_money
 * @property integer $create_time
 * @property integer $confirm_time
 * @property string $info
 * @property integer $state
 */
class Recharge extends \yii\db\ActiveRecord
{
    public static $states =[null=>'请选择','未审核','已审核','已打回'];
    public static $pay_type=[1=>'微信',2=>'支付宝',3=>'现金',4=>'转账'];
    public $username;
    private static $toCashIndex = [4];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%recharge}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'type', 'create_time', 'confirm_time', 'state','pay_type'], 'integer'],
            [['type', 're_money', 'create_time', 'state'], 'required'],

            [['re_money'], 'number'],
            [['info'], 'string', 'max' => 255],
            ['username','string'],
            ['username','checkMemberUser'],
            [['re_money'],'checkFrontendMoney','on'=>'f_add'],
            [['pay_time','pay_type'],'required','on'=>'f_add'],
            ['pay_time','safe'],
            ['type','in', 'range'=>static::$toCashIndex,'on'=>'f_add'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', '会员账户'),
            'member_id' => Yii::t('app', '会员编号'),
            'type' => Yii::t('app', '充值类型'),
            're_money' => Yii::t('app', '充值金额'),
            'create_time' => Yii::t('app', '充值时间'),
            'confirm_time' => Yii::t('app', '审核时间'),
            'info' => Yii::t('app', '备注'),
            'state' => Yii::t('app', '审核状态'),
            'pay_type' => Yii::t('app', '支付类型'),
            'pay_time' => Yii::t('app', '支付时间'),
        ];
    }
    //后台充值 检查是否有改会员
    public function checkMemberUser(){
        $name= $this->username;
        $model= Member::find()->where(['username'=>$name])->one();
        if($model){
            $this->member_id =$model->id;
        }else{
            return $this->addError('username','请输入正确的会员编号');
        }

    }
    // 前台验证充值金额
    public function checkFrontendMoney(){
        $money= $this->re_money;
        if($money<=0){
            return $this->addError('re_money','请输入正确的充值金额');
        }
    }
    // 下拉数组
    public static function getReList(){
       $types= Account::$name_array;
       $reArr = [];
       foreach (static::$toCashIndex as $key => $value) {
           if (isset($types[$value])) {
                $reArr[$value] = $types[$value];
           }
       }
       return $reArr;
    } 
    // 后台 充值确认 加金额
    public function ChongZhi(){
        if($this->update()){
            // $type= Account::$field_array[$this->type];
            // $re=Account::updateAllCounters([$type=>$this->re_money],['member_id'=>$this->member_id]);
            // $re=Account::updateAllCounters([$type=>$this->re_money],['member_id'=>$this->member_id]);
            Account::addAccount($this->member_id, $this->re_money, $this->type, '充值');
            return true;
            // if(empty($re)){
                // return false;
            // }
        }else{
            throw new \Exception("请重试");
            
        }
        
    }

    public function getAccount()
    {
        return $this->hasMany(Account::className(), ['member_id'=>'member_id']);
    }

    public static function getStates()
    {
        return self::$states;
    }

    public static function getState($type)
    {
        $states = self::getStates();
        $class = ['info','success','danger'];
       
        return Html::tag('span',$states[$type],['class'=>'label label-'.$class[$type]]);
    }
}