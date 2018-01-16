<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ToCashSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="to-cash-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'member_id') ?>

    <?= $form->field($model, 'bankname') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'username') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'to_money') ?>

    <?php // echo $form->field($model, 'tax') ?>

    <?php // echo $form->field($model, 'real_money') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'confirm_time') ?>

    <?php // echo $form->field($model, 'state') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
