<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Sort;
/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
$url = \yii\helpers\Url::to(['sort/get-list']);

?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'goods_name')->textInput(['maxlength' => true])?>
        
     <?= $form->field($model, 'big_id')->dropDownList(
        ArrayHelper::map(Sort::getParentsList(),'id','sort_name'),[
            'onchange' => "
                if($(this).val() != ''){
                     $.get('{$url}?id='+$(this).val(), function(data) {
                            $('#product-cate_id').html(data);
                        })
                }" 
     ])?>

    <?= $form->field($model, 'goods_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goods_img')->widget('common\widgets\file_upload\FileUpload',[
        'config'=>[
            ]
    ]) ?>
        
    <?= $form->field($model, 'inventory')->textInput() ?>
    
    <?= $form->field($model, 'content')->widget('common\widgets\ueditor\Ueditor',[
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


    <?= $form->field($model, 'market_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'present_price')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'is_show')->radioList(['1'=>'是','0'=>'否']) ?>

    <?= $form->field($model, 'is_top')->radioList(['1'=>'是','0'=>'否']) ?>

    <?= $form->field($model, 'is_hot')->radioList(['1'=>'是','0'=>'否']) ?>

    <?= $form->field($model, 'is_new')->radioList(['1'=>'是','0'=>'否']) ?>

    <?= $form->field($model, 'is_reg')->radioList(['1'=>'是','0'=>'否']) ?> -->

    <?= $form->field($model, 'is_login')->radioList(['1'=>'是','0'=>'否']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
