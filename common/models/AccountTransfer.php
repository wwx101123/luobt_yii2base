<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%account_transfer}}".
 *
 * @property integer $id
 * @property integer $out_id
 * @property integer $out_name
 * @property integer $into_id
 * @property integer $into_name
 * @property string $out_money
 * @property string $into_money
 * @property integer $type
 * @property string $info
 * @property integer $create_time
 */
class AccountTransfer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_transfer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['out_id', 'into_id', 'type', 'create_time'], 'integer'],
            [['into_name', 'out_money', 'type',], 'required'],
            [['out_money', 'into_money'], 'number'],
            [['out_name','into_name'],'string','max'=>50],
            [['info',], 'string'],
            [['into_name'],'CheckIntoName'],
            [['out_money'],'CheckOutMoney'],
            [['into_name'],'checkTeam'],
            // [['into_name'],'checkIsAgent'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'out_id' => Yii::t('app', '转出会员id'),
            'out_name' => Yii::t('app', '转出编号'),
            'into_id' => Yii::t('app', '转入会员id'),
            'into_name' => Yii::t('app', '转入编号'),
            'out_money' => Yii::t('app', '转出金额'),
            'into_money' => Yii::t('app', '到账金额'),
            'type' => Yii::t('app', '转账类型'),
            'info' => Yii::t('app', '备注信息'),
            'create_time' => Yii::t('app', '转账时间'),
        ];
    }
    public static function getTypes(){
        $accounts = [
            1=>  Account::$name_array[4].'  转 '.'其他会员',
            // 2=>  Account::$name_array[5].'  转 '.'其他会员',
        ];
        return $accounts;
    }
    public   function checkIsAgent(){
        $user = Member::find()->where(['id' =>$this->into_id])->andwhere(['is_agent' => 1])->one();
        if(empty($user)){
            $this->addError('into_name','只能给服务中心转账！');
        }
    }
    public static function getType($type){
        $getTypes = self::getTypes();
        return isset($getTypes[$type])?$getTypes[$type]:'未设置';

    }
    //检查转入会员和转出会员是否在一条直线
    public function checkTeam(){
        $into_model = Relationship::find()->select('member_id')->where(['member_id'=>$this->into_id])->andwhere(['like','re_path',$this->out_id.','])->asarray()->one();
        $out_model = Relationship::find()->select('member_id')->where(['member_id'=>$this->out_id])->andwhere(['like','re_path',$this->into_id.','])->asarray()->one();

        if($into_model || $out_model){
            return true;
        }else{
            $this->addError('into_name','您不允许向该成员转账！');
            return false;
        }
        
    }
    public function CheckIntoName(){
        $check_name = $this->into_name;
        $login_name = yii::$app->user->identity->username;
        if($check_name==$login_name){
            $this->addError('into_name','请勿给自己转账！');
            return false;
        }
        $member=Member::findByUsername($check_name);
        if($member){
            $this->into_id = $member->id;
        }else{
            throw new \Exception("该会员不存在,请重新输入！", 1);
        }
    }
    public function CheckOutMoney(){
        $login_id= yii::$app->user->identity->id;
        $user_account = Account::find()->where(['member_id' => $login_id])->one();
        if($this->out_money<=0){
            $this->addError('out_money','请输入正确的转账金额！');
            return false;
        }
        if($this->type==1){
           $check= $this->out_money > $user_account->account3;
           if($check){
                throw new \Exception("您的账户该积分不足,转换失败", 1);
           }
        }elseif($this->type==2){
           $check= $this->out_money > $user_account->account5;
           if($check){
                throw new \Exception("您的账户该积分不足,转换失败", 1);
           }
        }else{
                throw new \Exception("未知的错误", 1);
        }
    }
    /** 保存之前 */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                switch ($this->type) {
                    case '1':
                        Account::addAccount($this->out_id, -$this->out_money, 'account4', '转给会员'.$this->into_name);
                        Account::addAccount($this->into_id, $this->out_money, 'account4', '来自会员'.$this->out_name.'转账');
                        break;
                    default:
                        throw new \Exception("不支持的转账类型", 1);
                        break;
                }
            }
            return true;
            
        } else {
            return false;
        }
    }
    

}




















