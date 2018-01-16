<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FenhongSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fenhong-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uid') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'money') ?>

    <?= $form->field($model, 'rdt') ?>

    <?php // echo $form->field($model, 'f_amount') ?>

    <?php // echo $form->field($model, 'f_money') ?>

    <?php // echo $form->field($model, 'qi') ?>

    <?php // echo $form->field($model, 'dft') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
