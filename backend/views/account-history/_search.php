<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\AccountHistory;
/* @var $this yii\web\View */
/* @var $model common\models\AccountHistorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);?>
 <div class="row">
    <div class="col-lg-6">
        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'bz')->dropDownList(AccountHistory::$bz)->label('货币流向类型'); ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <?= $form->field($model, 'account')->dropDownList(AccountHistory::$AccountArr)->label('账户类型') ?>
    </div>
</div>

    <div class="col-lg-offset-5">
        <div class="form-group">
            <?= Html::submitButton('查找', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
