<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Cat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::button($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'j-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$requestUrl =yii\helpers\Url::toRoute($model->isNewRecord?['create']:['update','id'=>$model->id]);
$js = <<<JS
    commit('#j-btn','{$requestUrl}'); 
JS;

$this->registerJs($js);