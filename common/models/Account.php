<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%account}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $account2
 * @property string $account3
 * @property string $account4
 * @property string $account5
 * @property string $account6
 * @property string $account7
 * @property string $account8
 */
class Account extends \yii\db\ActiveRecord
{
    private static $backendRechargeIndex = [3, 4, 5];

    public static $field_array = [
        '2' => 'account2',
        '3' => 'account3',
        '4' => 'account4',
        '5' => 'account5',
        '6' => 'account6',
        '7' => 'account7',
        '8' => 'account8'
    ];

    public static $name_array = [
        '2' => '总奖金',
        '3' => '奖金积分',
        '4' => '注册积分',
        '5' => '消费积分',
        '6' => '',
        '7' => '',
        '8' => ''
    ];

    /**
     * 后台充值类型
     * @return [type] [description]
     */
    public static function backendRechargeAccountArr()
    {
        $arr = self::$name_array;
        foreach ($arr as $key => $value) {
            if (!in_array($key, self::$backendRechargeIndex)) {
                unset($arr[$key]);
            }
        }
        return $arr;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'integer'],
            [['account2', 'account3', 'account4', 'account5', 'account6', 'account7', 'account8'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $arr = [];
        foreach (self::$name_array as $key => $value) {
            $arr['account'. $key] = $value;
        }
        return array_merge([
            'member_id' => '会员编号',
        ], $arr);
    }

    public static function accountNameArr()
    {
        $arr = [];
        foreach (self::$name_array as $key => $value) {
            $arr['account'.$key] = $value;
        }
        return array_merge([
            'member_id' => '会员编号',
        ], $arr);
    }

    public static function accountNameByFile($file)
    {
        $arr = self::accountNameArr();
        return isset($arr[$file]) ? $arr[$file] : '未设置';
    }

    public static function gouwu($amount, $member_id)
    {
        return static::updateAllCounters(['account7'=>-$amount], ['and','member_id'=>$member_id,['>=', 'account7', $amount]]);
    }

    public static function addBonus($member_id, $amount, $bonusIndex, $bz='')
    {        
        $bonusName = Parameter::getBonusName($bonusIndex - 1);
        // 有一部分进消费积分
        $prii = Parameter::getShui($bonusIndex);
        $account5 = $amount * $prii;
        $account3 = $amount - $account5;
        $bzStr = $bonusName;
        if (!empty($bz)) {
            $bzStr .= '(' . $bz . ')';
        }
        self::addAccount($member_id, $account3, 3, $bzStr);
        self::addAccount($member_id, $account5, 5, $bzStr);
        self::changeAccount($member_id, 'account2', $amount);
    }

    public static function baodan($member_id, $amount, $bz='')
    {
        self::addAccount($member_id, $amount, 4, $bz);
        
    }

    public static function addAccount($member_id, $amount, $accountName, $bz='')
    {
        $file = is_numeric($accountName) ? 'account'. $accountName : $accountName;
        $name = self::accountNameByFile($file);
        if (!self::changeAccount($member_id, $file, $amount)) {
            throw new \Exception($name . "余额不足", 1);
        }         
        $model = new AccountHistory;
        $model->member_id = $member_id;
        $model->amount = $amount;
        $model->account = $name;
        $model->bz = $bz;
        $model->created_at = time();
        if (!$model->save()) {
            $str = '';
            // foreach ($model->getErrors() as $key => $er) {
            //     foreach ($er as $key => $value) {
            //         $str .= $value;
            //     }
            // }
            throw new \Exception("插入记录失败".$str, 1);            
        }
    }

    public static function changeAccount($member_id, $file, $amount)
    {
        if ($amount == 0) {
            return true;
        }
        return self::updateAllCounters([$file => $amount], ['and', ['member_id' => $member_id], ['>=', $file, -$amount]]);
    }

}
