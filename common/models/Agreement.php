<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%agreement}}".
 *
 * @property integer $id
 * @property string $contend
 */
class Agreement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%agreement}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
        ];
    }

    // public function beforeSave()
    // {
    //     if (parent::beforeSave($insert)) {
    //         // ...custom code here...
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'content' => Yii::t('app', '内容'),
        ];
    }
}
