<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%address}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property integer $postcode
 * @property string $tel
 * @property integer $status
 * @property integer $user_id
 */
 
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%address}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','address','tel'], 'required'],
            [['user_id'],'checkUser','on'=>'add'],
            [['postcode', 'status', 'user_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['address'], 'string', 'max' => 200],
            [['tel'], 'string', 'max' => 50],
        ];
    }

    public function checkUser()
    {
        $count=self::find()->where(['user_id'=>$this->user_id])->count();
        if ($count>=5) {
            $this->addError('address','最多只能添加5条收货地址');
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', '姓名'),
            'address' => Yii::t('app', '详细地址'),
            'postcode' => Yii::t('app', 'Postcode'),
            'tel' => Yii::t('app', '电话'),
            'status' => Yii::t('app', 'Status'),
            'user_id' => Yii::t('app', 'User ID'),
            'city_id'=>Yii::t('app','城市'),
            'area_id'=>Yii::t('app','区域'),
        ];
    }
    public static  function getMyAddress($address_id,$user_id)
    {
        $address = self::find()->where( ['user_id'=>$user_id,'id'=>$address_id] )->asArray()->One();
        if ($address) {
            return $address;
        }else{
            return '';
        }
    }
    public  function getAddress()
    {
        $address = self::find()->where( ['user_id'=>$this->user_id,'status'=>1] )->asArray()->One();
        if ($address) {
            return $address;
        }else{
            return '';
        }
    }

    public  function getAddressList()
    {
        $address = self::find()->where( ['user_id'=>$this->user_id] )->asArray()->all();
        return $address;
       
    }

    public function beforeSave($insert)  
    {  
        if(parent::beforeSave($insert))  
        {  
            if($this->isNewRecord)  
            {  
                $this->status = 1;
                self::updateAll(['status'=>0],'user_id = '.$this->user_id);
            }  
            return true;  
        }  
        else  
            return false;  
    }


}
