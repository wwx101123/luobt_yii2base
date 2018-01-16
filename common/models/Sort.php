<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sort}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $sort_name
 * @property integer $sort_order
 * @property string $attach_thumb
 * @property integer $status_is
 * @property integer $menu_is
 * @property integer $big_id
 * @property integer $grade
 * @property string $filter_attr
 * @property string $p_path
 */
class Sort extends \yii\db\ActiveRecord
{   

    const STATUS_DISABLE = 1;
    const STATUS_USE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sort}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['parent_id', 'sort_name', 'sort_order', 'attach_thumb', 'status_is', 'menu_is', 'big_id', 'grade', 'filter_attr', 'p_path'], 'required'],
            [['parent_id', 'sort_order', 'status_is','is_show'], 'integer'],
            [['sort_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', '上级分类'),
            'sort_name' => Yii::t('app', '分类名称'),
            'sort_order' => Yii::t('app', '排序'),
            'status_is' => Yii::t('app', '状态'),
            'is_show' => Yii::t('app', '是否首页显示'),

           
        ];
    }

    public static function getStatus()
    {
        return [
            // ''=>'全部',
            self::STATUS_DISABLE=>'禁用',
            self::STATUS_USE=>'使用',

        ];
    }

    public static function getDropDownList()
    {   
        $arr[] = array('id'=>0,'sort_name'=>'顶级分类');
        $temparray = self::getSortList();

        return array_merge($arr,$temparray);
    }



    public static function getSortList($parent_id=0,$level = 1,$add = 2, $repeat = "---" )
    {
        $list = self::find()->where(['parent_id'=>$parent_id])->asArray()->all();
        $str_repeat = "";
        if ($level) {
            for ($i=0; $i <$level ; $i++) { 
                $str_repeat .= $repeat;
                if ($i == $level-1) {
                    $str_repeat .= '--|';
                }
            }
        }
        $arry = array();
        $newarray = array ();
        $temparray = array ();
        foreach ($list as $key => $vo) {
            $newarray [] = array('id'=>$vo['id'],'sort_name'=>$str_repeat.$vo['sort_name'],'str_repeat'=>$str_repeat,'sort_order'=>$vo['sort_order'],);
            $temparray = self::getSortList ( $vo['id'], ($level + $add) );
            if ($temparray) {
                $newarray = array_merge ( $newarray, $temparray );
            }
        }
        return $newarray;
        // var_dump($list);
    }
    public static function getName($id)
    {
       $data = self::find()->where(['id'=>$id])->asArray()->one();
       if ($data) {
           return $data['sort_name'];
       }else{
            return '';
       }
    }
    
     public static function getParentsList()
    {
        $list = self::find()->where(['parent_id'=>0])->asArray()->all();
        $arr[] = array('id'=>0,'sort_name'=>'请选择');
        return array_merge($arr,$list);
    }

        public function beforeDelete()
    {
        $result = self::find()->where(['parent_id'=>$this->id])->one();
        if ($result) {
            throw new \Exception('请先删除子类', 1);
            return false;
        }
        return parent::beforeDelete();  
    }


}
