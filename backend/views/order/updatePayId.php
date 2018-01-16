<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Order;
/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

 

    <?= $form->field($model, 'pay_id')->dropDownList(['在线支付','货到付款']); ?>





    <div class="form-group">
        <?= Html::button($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'j-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php


$requestUrl =Url::toRoute($model->isNewRecord?['update-pay-id']:['update-pay-id','id'=>$model->id]);

$js = <<<JS
    commit('#j-btn','{$requestUrl}'); 
JS;


$this->registerJs($js);