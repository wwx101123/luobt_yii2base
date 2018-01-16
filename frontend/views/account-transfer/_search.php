<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AccountTransferSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-transfer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'out_id') ?>

    <?= $form->field($model, 'out_name') ?>

    <?= $form->field($model, 'into_id') ?>

    <?= $form->field($model, 'into_name') ?>

    <?php // echo $form->field($model, 'out_money') ?>

    <?php // echo $form->field($model, 'into_money') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
