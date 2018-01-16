<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property integer $id
 * @property integer $fuid
 * @property integer $tuid
 * @property string $fusername
 * @property string $tusername
 * @property string $title
 * @property string $content
 * @property integer $rdt
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fuid', 'tuid', 'rdt'], 'required'],
            [['fuid', 'tuid', 'rdt'], 'integer'],
            [['content'], 'string'],
            [['fusername', 'tusername'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 64],


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fuid' => Yii::t('app', 'Fuid'),
            'tuid' => Yii::t('app', 'Tuid'),
            'fusername' => Yii::t('app', '发件人'),
            'tusername' => Yii::t('app', '收件人'),
            'title' => Yii::t('app', '标题'),
            'content' => Yii::t('app', '内容'),
            'rdt' => Yii::t('app', '时间'),
            't_read' =>Yii::t('app', '阅读状态'),
            'f_read' =>Yii::t('app', '阅读状态'),
        ];
    }
}
