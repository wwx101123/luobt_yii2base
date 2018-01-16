<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%admin_login_info}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $username
 * @property integer $ip
 * @property integer $created_at
 */
class AdminLoginInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_login_info}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'ip', 'created_at'], 'integer'],
            [['username'], 'string', 'max' => 64],
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
            'username' => '用户名',
            'ip' => 'Ip地址',
            'created_at' => '登录时间',
        ];
    }
}
