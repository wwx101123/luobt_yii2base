<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%parameter}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $val
 * @property string $explain
 * @property integer $hidden
 * @property integer $show_type
 * @property integer $sort_num
 */
class Parameter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%parameter}}';
    }

    public static function getUlevel()
    {
        return self::getValArrById(1);
    }

    public static function getCpzjByLevel($index)
    {
        $arr = static::getCpzjArr();
        return isset($arr[$index]) ? $arr[$index] : NULL;
    }

    public static function getCpzjArr()
    {
        return self::getValArrById(2);
    }

    public static function getBonusName($index)
    {
        $arr = self::getBonusNameArr();
        return isset($arr[$index]) ? $arr[$index] : NULL;
    }

    public static function getBonusNameArr($unsetIndex = [])
    {
        $arr = self::getValArrById(4);
        // foreach ($arr as $key => $value) {
        //     if (in_array($key, $unsetIndex)) {
        //         unset($arr[$key]);
        //     }
        // }
        return $arr;
    }

    public static function getValById($id)
    {
        $model = static::findOne($id);
        return $model->val;
    }

    public static function getValArrById($id)
    {
        $model = static::findOne($id);
        $arr = explode("|", $model->val); // explode - 把字符串打散为数组
        return $arr;
    }

    public static function getDan()
    {
        $model = static::findOne(3);
        $arr = explode("|", $model->val);
        return $arr;
    }

    public static function getUlevelName($level = false)
    {
        $nameArr = static::getUlevel();
        return isset($nameArr[$level]) ? $nameArr[$level] : '未定义';
    }

    // 获取代理级别
    public static function getGlevelName($level = false)
    {
        $nameArr = static::getGlevel();
        return isset($nameArr[$level]) ? $nameArr[$level] : NULL;
    }

    public static function getGlevel()
    {
        $arr = self::getValArrById(5);
        $a = ['无'];
        foreach ($arr as $key => $value) {
            $a[$key + 1] = $value;
        }
        return $a;
    }

    public static function getArea()
    {
        $arr = ['左区', '右区'];
        // $arr = ['左区', '中区', '右区'];
        return $arr;
    }

    public static function getAgentArr()
    {
        return ['否', '是'];
    }
    //提现手续费
    public static function getToCashTax(){
        $val = static::getValById(7);
        $val /= 100;
        return $val;
    }
    //扣税
    public static function getShui($index=NULL){
        $val = $index == 5 ? static::getValById(24) : static::getValById(21);
        $val /= 100;
        return $val;
    }
    //最低提现金额
    public static function getMinMoney(){
        $val= static::getValById(10);
        return $val;
    }
    //最大提现金额
    public static function getMaxMoney(){
         $val= static::getValById(12);
         // $val =10000;
        return $val;
    }
    
    //提现倍数
    public static function getMultipleMoney(){
        $val= static::getValById(11);
        return $val;
    }
    // 前台开关处理
    public static function getFrontendSwitch(){
        $val = static::getValById(13);
        return $val;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hidden', 'show_type', 'sort_num'], 'integer'],
            [['name', 'explain'], 'string', 'max' => 32],
            [['val'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '参数名',
            'val' => '值',
            'explain' => '说明',
            'hidden' => '是否隐藏',
            'show_type' => '显示类型',
            'sort_num' => '排序值',
        ];
    }
}
