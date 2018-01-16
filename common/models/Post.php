<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $label_img
 * @property integer $cat_id
 * @property integer $is_show
 * @property integer $is_top
 * @property integer $create_at
 * @property integer $updated_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['cat_id', 'is_show', 'is_top', 'create_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 55],
            [['label_img'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', '新闻标题'),
            'content' => Yii::t('app', '内容'),
            'label_img' => Yii::t('app', '配图'),
            'cat_id' => Yii::t('app', '类型'),
            'is_show' => Yii::t('app', '是否显示'),
            'is_top' => Yii::t('app', '是否置顶'),
            'create_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
        //上一篇
    public static function Prev($id){
        $prev=self::find()->where(['>','id',$id])->andwhere(['is_show'=>1])/*->andwhere(['=','cat_id','0'])*/->asArray()->one();
        return $prev;
}
    //下一篇
    public static function Next($id){

        $Next=self::find()->where(['<','id',$id])->andwhere(['is_show'=>1])/*->andwhere(['=','cat_id','0'])*/->orderby('id desc')->asArray()->one();  
        return $Next;     
    }


}
