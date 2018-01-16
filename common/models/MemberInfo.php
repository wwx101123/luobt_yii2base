<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member_info}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $phone
 * @property string $code
 */
class MemberInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id'], 'integer'],
            [['name', 'phone', 'code'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_id' => 'Member ID',
            'name' => '姓名',
            'phone' => '手机号',
            'code' => '身份证',
        ];
    }
}
