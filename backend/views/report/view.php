<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use common\models\Report;
use common\models\Member;
/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '留言列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    
.text-explode{
    color:#9aa09a;
}
.msg_box{
    margin-top:12px;margin-bottom:10px;background: #d7e3f3;padding: 30px;
}
.msg_content{
    background: #fff;
    border: 1px solid #ddd;
    padding: 20px;
    margin-bottom: 20px;
}
.message-item {
    position: relative;
    padding: 16px;
    border-top: 1px dashed #bcbcbc;
}
 .message-user span{
    color:#9aa09a;
}
.table-viewer-header {
    margin-top: 10px;
    margin-bottom: -1px;
    height: 40px;
    background: #F5f6FA;
    line-height: 38px;
    border: 1px solid #e1e6eb;
    position: relative;
    border-left: 4px solid #6d7781;
}
.table-viewer-header .table-viewer-topbar-title {
    font-size: 14px;
    color: #333333;
    display: inline-block;
    margin-left: 16px;
}
.huifu-content{
    background: #fff;
    padding:20px;
}
</style>

<div class="report-view">
    <div class="msg_box">
        <div class="row" >
            <div class="col-sm-2">
                <span class="text-explode">
                <span>问题标题</span> <span>:</span></span>
                <?php echo $model->title ?>
            </div>
            <div class="col-sm-2">
                <span class="text-explode">
                <span>提问会员</span> <span>:</span></span>
                <?php echo $model->member->username?>
            </div>
        </div>
        <div class="row" style="margin-top: 4px">
            <div class="col-xs-4">
                <span class="text-explode">
                    <span>提交时间</span> <span>:</span>
                </span>
                <?php echo date('Y-m-d H:i:s',$model->create_time) ?>
            </div>
            <div class="col-xs-3">
                <span class="text-explode">
                    <span>状态</span> <span>:</span>
                </span>
                <?php echo  Report::$states[$model->is_read] ?>
            </div>
        </div>
    </div>

    <div class="table-viewer-header clearfix">
        <span class="table-viewer-topbar-title">
            <span >沟通记录</span>
        </span>
    </div>

    <div class="msg_content">
        <?= $model->content; ?>
        <?php foreach ($model['reportMsg'] as $key => $var): ?>
        <div class="message-item 
            <?php if ($var->name != '管理员'): ?>
                  message-user
            <?php endif; ?>">
            <div class="row">
                <div class="col-sm-2">
                    <span ><?php echo $var->name ?></span> <span >:</span>
                    <span>
                        <?php echo $var->content ?>
                    </span>
                </div>
            </div>
            <div class="item-time text-explode">
                <?php echo date('Y-m-d H:i:s',$var->create_time) ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($model->status == 0): ?>   
        <?php $form = ActiveForm::begin() ?>
        <div class="table-viewer-header clearfix">
            <span class="table-viewer-topbar-title">
            <span >回复</span></span>
        </div>
        <div class="huifu-content">
        <?= $form->field($model_msg, 'content')->textarea(['rows' => 6,'cols'=>50,'class'=>''])->label(false) ?>
            <p style="margin-top:10px;"><button class="btn btn-success">提交</button></p> 
        </div>
        <?php ActiveForm::end() ?>
     <?php endif ?>
     
</div>
