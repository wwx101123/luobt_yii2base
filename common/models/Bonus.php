<?php

namespace common\models;

use Yii;
use common\models\Member;
/**
 * This is the model class for table "{{%bonus}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $bonus_type
 * @property integer $account_type
 * @property integer $create_time
 * @property integer $today_time
 * @property integer $clear_time
 * @property string $amount
 * @property string $start_amount
 * @property string $end_amount
 * @property string $bz
 * @property integer $state
 * @property integer $reg_id
 */
class Bonus extends \yii\db\ActiveRecord
{

    public $b_all;
    public $b0;
    public $b1;
    public $b2;
    public $b3;
    public $b4;
    public $b5;
    public $b6;
    public $b7;
    public $b8;
    public $b9;
    public $b10;
    public $b11;

    public $income;
    public $profit;
    public $ratio;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bonus}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'bonus_type', 'account_type', 'create_time', 'today_time', 'clear_time', 'state', 'reg_id'], 'integer'],
            [['amount', 'start_amount', 'end_amount','income','profit','ratio'], 'number'],
            [['bz'], 'string', 'max' => 64],
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
            'bonus_type' => 'Bonus Type',
            'account_type' => '名称',
            'create_time' => '时间',
            'today_time' => '本期时间',
            'clear_time' => '到账时间',
            'amount' => '金额',
            'start_amount' => '原金额',
            'end_amount' => '新金额',
            'bz' => '备注',
            'state' => 'State',
            'reg_id' => 'Reg ID',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }
}
