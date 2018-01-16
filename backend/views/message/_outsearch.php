<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MessageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-search">

    <?php $form = ActiveForm::begin([
        'action' => ['outbox'],
        'method' => 'get',
        'fieldConfig' => [  
            'template' => "{input}",
        ],
    ]); ?>

    <?= $form->field($model, 'tusername', ['options'=>['class'=>'col-md-2']])->textInput(['placeholder'=>'收件人']) ?>

    <div class="form-group col-md-2">
        <?= Html::submitButton('查找', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
