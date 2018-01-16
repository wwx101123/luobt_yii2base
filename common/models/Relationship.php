<?php

namespace common\models;

use Yii;
use common\models\Member;

/**
 * This is the model class for table "{{%relationship}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $father_id
 * @property integer $re_id
 * @property string $p_path
 * @property string $re_path
 */
class Relationship extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%relationship}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'father_id', 're_id'], 'integer'],
            [['p_path', 're_path'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => 'Member ID',
            'father_id' => '接点人ID',
            're_id' => '推荐人ID',
            'p_path' => 'P Path',
            're_nums' => '推荐人数',
            're_path' => 'Re Path',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }

    public static function addDuipengInfo($father_id, $area, $num=0, $money)
    {
        if ($num == 0) {
            return false;
        }
        $model = self::find()->joinWith('member')->where(['member_id' => $father_id])->one();
        if (!$model) {
            return false;
        }
        switch ($area) {
            case '0':
                self::updateAllCounters(['l' => $num, 'sl' => $num, 'l_money' => $money], ['member_id' => $model->member_id]);
                break;
            case '1':
                self::updateAllCounters(['r' => $num, 'sr' => $num, 'r_money' => $money], ['member_id' => $model->member_id]);
                break;
            case '2':
                self::updateAllCounters(['lr' => $num, 'slr' => $num], ['member_id' => $model->member_id]);
                break;
            default:
                # code...
                break;
        }
        static::addDuipengInfo($model->father_id, $model->area, $num,$model->member->cpzj);
    }

    public function getMemberInfo()
    {
        return $this->hasOne(MemberInfo::className(), ['member_id'=>'member_id']);
    }

}
