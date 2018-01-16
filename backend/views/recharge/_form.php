<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Member;
use common\models\Account;
/* @var $this yii\web\View */
/* @var $model common\models\Charge */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="charge-form">

    <?php $form = ActiveForm::begin([   
        'id' => 'charge-form',
        'method' => 'post',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "<div class='col-xs-3 col-sm-0 text-right'>{label}:</div> <div class='col-xs-9 col-sm-9'>{input}</div> <div class='col-xs-12 col-xs-offset-3 col-sm-3 col-sm-offset-3'>{error}</div>",
        ]
    ]);?>

    <div class="row">
        <div class="col-lg-9">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]); ?>
        </div>
    </div>    

    <div class="row">
        <div class="col-lg-9">
            <?= $form->field($model, 'type')->dropDownList(Account::backendRechargeAccountArr()); ?>
        </div>
    </div>
      
    <div class="row">  
        <div class="col-lg-9">    
             <?= $form->field($model, 're_money')->textInput(); ?> 
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
          <?= $form->field($model, 'info')->textarea(['rows' => 3]); ?>
        </div>
    </div>

    <div class="col-lg-offset-5">
        <div class="form-group">
          <?= Html::submitButton($model->isNewRecord ? '确认充值' : '确认编辑', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']); ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
