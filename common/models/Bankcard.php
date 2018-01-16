<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%bankcard}}".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $bankname
 * @property string $number
 * @property string $province
 * @property string $city
 * @property string $address
 * @property string $username
 */
class Bankcard extends \yii\db\ActiveRecord
{
    public $show ;//用于提现显示银行信息
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bankcard}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bankname','number','username','province','city','address'],'required'],
            [['member_id'], 'integer'],
            [['bankname', 'number', 'province', 'city', 'username'], 'string', 'max' => 32],
            [['address'], 'string', 'max' => 255],
            [['number'],'match','pattern'=>'/^(\d{16}|\d{19})$/','message'=>yii::t('app','您的银行卡号输入格式不对')],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '会员编号',
            'bankname' => '银行名称',
            'number' => '银行卡号',
            'province' => '省份',
            'city' => '城市',
            'address' => '详细开户地址',
            'username' => '开户姓名',
        ];
    }
    public static function getBankData(){
        $model = self::find()->where(['member_id'=>yii::$app->user->identity->id])->all();
        $data=[];
        foreach ($model as $key => $v) {
                $v->show=$v->bankname.'|'.$v->number.'|'.$v->username;
                $data= ArrayHelper::map($model,'id','show');
            }
        return $data;
    }
    
    public static function getDefaultCardId(){
        $model = self::find()->where(['member_id'=>yii::$app->user->identity->id])->one();
        return $model ? $model->id : NULL;
    }
}
