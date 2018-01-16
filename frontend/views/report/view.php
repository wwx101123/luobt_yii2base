<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Report;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '我的反馈'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .msg_box {
    margin-top: 12px;
    margin-bottom: 10px;
    background: #d7e3f3;
    padding: 30px;
}
.title {
    height: 37px;
    line-height: 37px;
    font-size: 16px;
    color: #fff;
    background: #434a54;
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
.msg_content {
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
.table-viewer-header .table-viewer-topbar-title {
    font-size: 14px;
    color: #333333;
    display: inline-block;
    margin-left: 16px;
}
.t7 {
    padding-left: 10px;
    background: url(../images/icon7.png) no-repeat 28px center;
}
.message-user span {
    color: #9aa09a;
}
.text-explode {
    color: #9aa09a;
}
</style>
         <div class="title">
        <span class="t7"><?php echo Yii::t('app', '明细')?></span>
        </div>
        <div class="msg_box">
            <div class="row" ">
                <div class="col-sm-2">
                    <span class="text-explode">
                    <span ><?php echo Yii::t('app', '问题标题')?> </span> <span >:</span>
                    </span>
                    <?php echo $model->title ?>

                </div>
            </div>
            <div class="row" style="margin-top: 4px">
                <div class="col-xs-4">
                    <span class="text-explode">
                    <span ><?php echo Yii::t('app', '提交时间')?></span> <span >:</span>
                    </span>
                    <?php echo date('Y-m-d H:i:s',$model->create_time) ?>

                </div>
                <div class="col-xs-3">
                    <span class="text-explode">
                    <span ><?php echo Yii::t('app', '状态')?></span> <span >:</span>
                    </span>
                    <?php echo  Report::get_status($model->is_read)?>

                </div>
            </div>
        </div>
        <div class="table-viewer-header clearfix">
            <span class="table-viewer-topbar-title">
            <span ><?php echo Yii::t('app', '沟通记录')?></span></span>
        </div>
        <div class="msg_content">
            <?=$model->content?>
            <?php foreach ($model['reportMsg'] as $key => $var): ?>
                <div class="message-item 
                <?php if ($var->name!='管理员'): ?>
                      message-user
                 <?php endif ?>">
                    <div class="row" ">
                        <div class="col-sm-12">

                            <span ><?php echo $var->name ?></span> <span >:</span>

                            <span >
                                <?php echo $var->content ?>
                            </span>
                        </div>
                    </div>
                     <div class="item-time text-explode"><?php echo date('Y-m-d H:i:s',$var->create_time) ?></div>
                </div>
            <?php endforeach ?>
           
        </div>
        <?php if ($model->status==0): ?>
            
       
        <?php $form = ActiveForm::begin() ?>
        <div class="table-viewer-header clearfix">
            <span class="table-viewer-topbar-title">
            <span ><?php echo Yii::t('app', '回复')?></span></span>
       </div>
       <div class="huifu-content">


        <?= $form->field($model_msg, 'content')->label(false)->widget('common\widgets\ueditor\Ueditor',[
            'options'=>[
                'initialFrameWidth' => '100%',
                'initialFrameHeight' => 200,
                'toolbars' => [
                    [
                        'fullscreen', 'undo', 'redo', '|',
                        'bold', 'italic','formatmatch', '|',
                        'forecolor', 'insertorderedlist','insertunorderedlist','fontsize', '|', 
                        'link', 'unlink', 'anchor', '|',
                        'horizontal','insertcode', '|',
                        'simpleupload', 'insertimage', 
                    ]
                ],   
            ]
        ]) ?>
           <p style="margin-top:10px;"><button class="btn btn-success"><?php echo Yii::t('app', '提交')?></button></p> 
       </div>
        <?php ActiveForm::end() ?>
         <?php endif ?>

