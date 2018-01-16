<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%account_history}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $amount
 * @property integer $account
 * @property string $bz
 * @property integer $created_at
 */
class AccountHistory extends \yii\db\ActiveRecord
{
    public $username;
    public static $AccountArr = ['All'=>'全部','奖金积分'=>'奖金积分','消费积分'=>'消费积分','注册积分'=>'注册积分'];
    public static $bz = ['All'=>'全部','分红'=>'分红','充值'=>'充值','提现'=>'提现','购物'=>'购物','推荐奖'=>'推荐奖','报单奖'=>'报单奖','级别津贴'=>'级别津贴','开通会员'=>'开通会员','积分转换'=>'积分转换','转给会员'=>'积分转账'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'created_at'], 'integer'],
            [['account', 'bz'], 'string', 'max' => 32],
            [['username'],'string','max' => 50],
            [['amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'amount' => '金额',
            'account' => '账户',
            'bz' => '备注',
            'created_at' => '时间',
            'username'=>'会员编号',
        ];
    }


}
