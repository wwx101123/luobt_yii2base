<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Parameter;

/* @var $this yii\web\View */
/* @var $model common\models\UserWeixin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-weixin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'val')->textInput(['maxlength' => true]) ?>

   

    <div class="form-group">
        <?= Html::button($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'j-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$requestUrl =yii\helpers\Url::toRoute(['update-val','id'=>$model->id]);
$js = <<<JS
    commit('#j-btn','{$requestUrl}'); 
JS;

$this->registerJs($js);