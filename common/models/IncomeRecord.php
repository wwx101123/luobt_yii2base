<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ld_income_record".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $money
 * @property integer $create_time
 * @property string $bz
 */
class IncomeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%income_record}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'money', 'create_time', 'bz'], 'required'],
            [['user_id', 'create_time'], 'integer'],
            [['money'], 'number'],
            [['bz'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '会员ID',
            'money' => '金额',
            'create_time' => '时间',
            'bz' => '备注',
        ];
    }

    public static function writeToRecord($user_id, $money, $bz, $time = null)
    {
        $model = new self;
        $model->user_id = $user_id;
        $model->money = $money;
        $model->bz = $bz;

        if ($time) {
            $model->create_time = $time;
        } else {
            $model->create_time = strtotime(date('Y-m-d'));
        }
        if (!$model->save()) {
            throw new \Exception("保存失败");            
        }
    }

    public static function getIncome($today_time)
    {
        $total = self::find()->where(['create_time' => $today_time])->orderBy(['id' => SORT_ASC])->asArray()->sum('money');
        return $total;
    }

    public static function getRatio($today_time, $pay)
    {
        $income = self::getIncome($today_time);
        if ($income == 0) {
            $ratio = 0;
        } else {
            $ratio = sprintf("%.3f", $pay/$income);
        }
        return ($ratio * 100) . '%';
    }
}
