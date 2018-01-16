<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Parameter;

$this->title = '注册会员';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="col-md-8 col-md-offset-1 form-horizontal">

    <!-- <div class="row"> -->
        <!-- <div class="col-lg-5"> -->
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'shop_name')->textInput() ?>
                <?= $form->field($model, 'username')->textInput() ?>
                <?= $form->field($model, 'u_level')->dropDownList(Parameter::getUlevel()) ?>
                <?= $form->field($model, 're_name')->textInput() ?>
                <?= $form->field($model, 'father_name')->textInput() ?>
                <?= $form->field($model, 'area')->dropDownList(Parameter::getArea()) ?>

                <?php //echo $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'code')->textInput() ?>
                <?= $form->field($model, 'phone')->textInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        <!-- </div> -->
    <!-- </div> -->
    </div>
</div>
</div>
