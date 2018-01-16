<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Cat;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'cat_id')->dropDownList(Cat::enumItems()) ?>
    
    <?php /*= $form->field($model, 'label_img')->widget('common\widgets\file_upload\FileUpload',[
        'config'=>[
            ]
    ]) */ ?>
        
    <?= $form->field($model, 'content')->widget('common\widgets\ueditor\Ueditor',[
            'options'=>[
                'initialFrameWidth' => '100%',
                'initialFrameHeight' => 800,
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
        
    <?= $form->field($model, 'is_top')->radioList(['1'=>'是','0'=>'否']) ?>
    <?= $form->field($model, 'is_show')->radioList(['1'=>'是','0'=>'否']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
