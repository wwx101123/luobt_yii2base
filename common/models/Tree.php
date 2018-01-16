<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
* 结构图用到的一些方法
*/
class Tree extends Model
{
    const TREE_IMG_AGENT = '@web/statics/images/tree/Official5.png';
    const TREE_IMG_ACTIVE = '@web/statics/images/tree/Official1.png';
    const TREE_IMG_INACTIVE = '@web/statics/images/tree/Official0.png';

    public static $ajaxStrArr = [
        self::TREE_IMG_AGENT => "服务中心", 
        self::TREE_IMG_ACTIVE => "已激活",
        self::TREE_IMG_INACTIVE => "未激活",
    ];
    
    public static function getAjaxTreeImg($user)
    {
        if ($user->is_agent == 1) {
            return self::TREE_IMG_AGENT;
        }
        else {
            return $user->activate ? self::TREE_IMG_ACTIVE : self::TREE_IMG_INACTIVE;
        }
    }

    public static function getLine($num, $aGroupNum)
    {
        $str = '';
        $bei = $num * 2;
        
        $emptyNum = 0;
        for ($i=0; $i < $bei; $i++) {
            $width = 100 / $bei;
            //去头尾
            if ($i == 0 || $i == $bei - 1) {
                $str .= '<div style="float: left;height:64px;width:'.$width.'%"></div>';
            }
            else {
                if ($emptyNum == 0) {
                    // 组合DIV
                    $str .= '<div class="box_inbox0'.$aGroupNum.'" style="height:64px;width:'.($width * ($aGroupNum - 1) * 2).'%;float: left;">
                                     <div class="inbox_left_2"></div>
                                     <div class="inbox_right_2"></div>
                                     <div class="clear"></div>
                                   </div>';
                    $i += 2 * $aGroupNum - 2 - 1;
                    $emptyNum = 2;
                }
                else {
                    $emptyNum--;
                    $str .= '<div style="float: left;height:64px;width:'.$width.'%"></div>';

                }
            }
        }
        return $str;                      

    }

    public static function getTipJsonData($model)
    {
        return json_encode(['u_level'=>$model->member->u_level, 
            'nick_name'=>$model->member->memberInfo->name,
            'username'=>$model->member->username,
            'l'=>$model->l,
            'r'=>$model->r,
            'sl'=>$model->sl,
            'sr'=>$model->sr,
            'lr'=>$model->lr,
            'slr'=>$model->slr,
            ]);
    }
}