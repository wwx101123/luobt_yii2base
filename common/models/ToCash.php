<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
/**
 * This is the model class for table "{{%to_cash}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $bankname
 * @property string $number
 * @property string $username
 * @property string $address
 * @property string $to_money
 * @property string $tax
 * @property string $real_money
 * @property integer $type
 * @property integer $create_time
 * @property integer $confirm_time
 * @property integer $state
 */
class ToCash extends \yii\db\ActiveRecord
{
    public static $states =[null=>'请选择','未审核','已审核','已打回'];
    public $bankcard_id;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%to_cash}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'type', 'create_time', 'confirm_time', 'state'], 'integer'],
            [['bankname','to_money','type'], 'required'],
            [['to_money', 'tax', 'real_money'], 'number'],
            [['bankname', 'username'], 'string', 'max' => 50],
            [['number'], 'string', 'max' => 32],
            [['address'], 'string', 'max' => 100],
            [['to_money'],'checkToMoney','on'=>'f_add'],
            [['to_money'],'checkMinToMoney','on'=>'f_add'],
            [['to_money'],'checkMultipleToMoney','on'=>'f_add'],
            [['state'],'checkState','on'=>'f_add'],
            [['bankcard_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_id' => Yii::t('app', '会员编号'),
            'bankname' => Yii::t('app', '开户银行'),
            'number' => Yii::t('app', '银行卡号'),
            'username' => Yii::t('app', '开户姓名'),
            'address' => Yii::t('app', '开户地址'),
            'to_money' => Yii::t('app', '交易金额'),
            'tax' => Yii::t('app', '手续费'),
            'real_money' => Yii::t('app', '实发金额'),
            'type' => Yii::t('app', '提现类型'),
            'create_time' => Yii::t('app', '提现时间'),
            'confirm_time' => Yii::t('app', '审核时间'),
            'state' => Yii::t('app', '审核状态'),
        ];
    }
       public static function getToList(){
       $types= Account::$name_array;
       unset($types[2]);
       unset($types[4]);
       unset($types[5]);
       unset($types[6]);
       unset($types[7]);
       unset($types[8]);
       return $types;
    } 
    public static function getTypeName($id){
        $TypeName=Account::$name_array[$id];
        $re= $TypeName?$TypeName:'未设置';
        return $re;
    }
    //检查是否已经后台审核
    public function checkState(){
        $cach_list= self::find()->where(['member_id'=>$this->member_id])->andwhere(['state'=>0])->one();
        if($cach_list){
            throw new \Exception('你还有一笔提现未处理', 1);
        }
    }
    //检查账户余额是否大于等于提现金额
    public function checkToMoney(){
        $account_money=Account::find()->where(['member_id'=>$this->member_id])->one();
        if($account_money->account3>=$this->to_money){
            throw new \Exception("账户积分余额不足,提现失败", 1);
        }
    }
    //限制最低提现金额
    public function checkMinToMoney(){
        $min = Parameter::getMinMoney();
        $max = Parameter::getMaxMoney();
        if($this->to_money<$min){
            throw new \Exception("提现失败,最低提现额度".$min, 1);
        }
        if($this->to_money>$max){

            throw new \Exception("提现失败,最高提现额度".$max, 1);
        }
    }
    //最低提现倍数
    public function checkMultipleToMoney(){
        $multiple= Parameter::getMultipleMoney();
        if ($this->to_money % $multiple) {
            throw new \Exception("提现失败,提现金额需为".$multiple.'倍数', 1);

        }

    }
    //银行卡属性赋值
    public function addBankCard(){
        $bank =Bankcard::find()->where(['id'=>$this->bankcard_id,'member_id'=>$this->member_id])->one();
        
        if($bank){
            $this->bankname = $bank->bankname;
            $this->number = $bank->number;
            $this->username = $bank->username;
            $this->address = $bank->address;
        }else{
            throw new \Exception("请先添加银行卡", 1);
        }   
    }
    public function attMoney(){
        $data_tax = Parameter::getToCashTax();
        $this->tax = $this->to_money*$data_tax;//手续费
        $this->real_money = $this->to_money - $this->tax;//实发金额

    }

    /** 保存之前 */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                Account::addAccount($this->member_id, -$this->to_money, 'account3', $bz='提现');
            }
            return true;
            
        } else {
            return false;
        }
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
