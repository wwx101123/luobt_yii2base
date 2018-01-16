<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
/**
 * This is the model class for table "{{%ap_agent}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $create_time
 * @property integer $confirm_time
 * @property integer $state
 */
class ApAgent extends \yii\db\ActiveRecord
{
    public static $states = [null => '请选择', '未审核', '已审核', '已拒绝'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ap_agent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'create_time', 'confirm_time', 'state'], 'integer'],
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
            'create_time' => Yii::t('app', '申请时间'),
            'confirm_time' => Yii::t('app', '审核时间'),
            'state' => Yii::t('app', '审核状态'),
        ];
    }

    public static function getStates()
    {
        return self::$states;
    }

    public static function getState($type)
    {
        $states = self::getStates();
        // var_dump($states);exit;
        $class = ['info', 'success', 'danger'];
        return Html::tag('span', $states[$type], ['class' => 'label label-'. $class[$type]]);
    }
}
