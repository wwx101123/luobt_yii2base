<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Report */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-form">

    <?php $form = ActiveForm::begin(
[
'fieldConfig' => [
          'template' => " <dl><dt>{label}</dt><dd > {input}<span class='gray'>{hint}</dd></dl><div class=''>{error}</div>",
          'options'=>['class'=>'']
          ]
]
    ); ?>

    <?= $form->field($model, 'user_name')->textInput()->label('收件人注册编号') ?>

    <?= $form->field($model, 'title')->textInput() ?>


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

    <div class="form-group" style="padding-top: 10px;">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '提交') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
