<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%member_in}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $created_at
 */
class MemberIn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member_in}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'created_at'], 'integer'],
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
            'created_at' => 'Created At',
        ];
    }
}
