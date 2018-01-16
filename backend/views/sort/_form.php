<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Sort;
/* @var $this yii\web\View */
/* @var $model common\models\Sort */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sort-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(Sort::getDropDownList(),'id','sort_name')) ?>

    <?= $form->field($model, 'sort_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'status_is')->dropDownList(['1'=>'使用','0'=>'禁用']) ?>

    <?= $form->field($model, 'is_show')->dropDownList(['1'=>'使用','0'=>'禁用']) ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '修改'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
