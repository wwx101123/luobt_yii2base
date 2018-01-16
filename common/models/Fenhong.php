<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%fenhong}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $amount
 * @property string $money
 * @property integer $rdt
 * @property integer $f_amount
 * @property string $f_money
 * @property integer $qi
 * @property integer $dft
 */
class Fenhong extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fenhong}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'amount', 'rdt', 'f_amount', 'qi', 'dft'], 'integer'],
            [['money', 'f_money'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'amount' => '可收益天数',
            'money' => '消费额',
            'rdt' => '消费时间',
            'f_amount' => '当前收益天数',
            'f_money' => '当前累计收益',
            'qi' => '期数',
            'dft' => '最新收益时间',
        ];
    }
}
