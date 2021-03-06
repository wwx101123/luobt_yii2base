<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

 

    <?= $form->field($model, 'goods_amount')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'order_amount')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::button($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'j-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php


$requestUrl =Url::toRoute($model->isNewRecord?['create']:['update','id'=>$model->id]);

$js = <<<JS
    commit('#j-btn','{$requestUrl}'); 
JS;


$this->registerJs($js);