<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%account_change}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $old_money
 * @property string $new_money
 * @property integer $type
 * @property integer $create_time
 * @property string $money
 */
class AccountChange extends \yii\db\ActiveRecord
{
    public $account3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_change}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'],'checkType'],
            [['member_id', 'old_money', 'new_money', 'type', 'create_time', 'money'], 'required'],
            [['member_id', 'type', 'create_time'], 'integer'],
            [['old_money', 'new_money', 'money'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'member_id' => Yii::t('app', 'User ID'),
            'old_money' => Yii::t('app', '转换前金额'),
            'new_money' => Yii::t('app', '转换后金额'),
            'type' => Yii::t('app', '转换类型'),
            'create_time' => Yii::t('app', '操作时间'),
            'money' => Yii::t('app', '转换金额'),
        ];
    }

    public static function getTypes()
    {
        $accounts = [
            0 => Account::$name_array[3].'  转 '.Account::$name_array[4],
            // 1 => Account::$name_array[3].'  转 '.Account::$name_array[4],
            1 => Account::$name_array[3].'  转 '.Account::$name_array[5],
        ];
        return $accounts;
    }

    public static function getMoneyType($type){
        $getTypes = self::getTypes();
        return isset($getTypes[$type])?$getTypes[$type]:'未设置';

    }

    public function checkType()
    {   
        if ($this->money<=0) {
            $this->addError('money','金额有误');
            return false;
        }
       
    }
    public function DisposalMoney(){
        $user_money= Account::find()->where(['member_id'=>$this->member_id])->one();
        /*获取转换成功的类型前后金币*/
        if ($this->type == 0) {
            $this->old_money = $user_money->account4;
            $this->new_money =  $this->old_money + $this->money;
        } elseif ($this->type == 1){
            $this->old_money = $user_money->account5;
            $this->new_money =  $this->old_money + $this->money;
        } elseif ($this->type == 2){
            $this->old_money = $user_money->account3;
            $this->new_money =  $this->old_money + $this->money;
        }
        switch ($this->type) {
            case 0:
                // $this->checkAccount($user_money, 'account3');
                // Account::updateAllCounters(['account3'=>-$this->money],['member_id'=>$this->member_id]);
                // Account::updateAllCounters(['account4'=>$this->money],['member_id'=>$this->member_id]);
                Account::addAccount($this->member_id, -$this->money, 'account3', '积分转换');
                Account::addAccount($this->member_id, $this->money, 'account4', '积分转换');
                break;
            case 1:
                // $this->checkAccount($user_money, 'account3');
                // Account::updateAllCounters(['account3'=>-$this->money],['member_id'=>$this->member_id]);
                // Account::updateAllCounters(['account5'=>$this->money],['member_id'=>$this->member_id]);
                Account::addAccount($this->member_id, -$this->money, 'account3', '积分转换');
                Account::addAccount($this->member_id, $this->money, 'account5', '积分转换');
                break;            
            // case 2:
            //     $this->checkAccount($user_money, 'account8');
            //     Account::updateAllCounters(['account8'=>-$this->money],['member_id'=>$this->member_id]);
            //     Account::updateAllCounters(['account3'=>$this->money],['member_id'=>$this->member_id]);
            //     break;
            default:
                break;
        }
    }

    public function checkAccount($model,$accountName)
    {
        if (!isset($model[$accountName])) {
            throw new \Exception("账户类型错误", 1);
        }

        if($model[$accountName] < $this->money){
            throw new \Exception("您的账户余额不足，转换失败".$model[$accountName], 1);
        }

    }

}
