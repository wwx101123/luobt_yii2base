<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Area;
/* @var $this yii\web\View */
/* @var $model common\models\Address */
/* @var $form yii\widgets\ActiveForm */
?>
    <div class="address_input_box">
    
    <ul class="add_address_ul">

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data','class' => 'form-horizontal'],
        'fieldConfig' => [  //统一修改字段的模板
            'template' => "
                            <div class='col-lg-2'></div>
                            <div class='col-lg-3 text-right'>{label}:</div>
                            <div class='col-lg-7'>{input}</div>

                        <div class='col-lg-offset-5 col-lg-7 '>{error}</div>",
            'labelOptions' => ['class' => ''],  //修改label的样式
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true,'placeholder'=>'姓名','style'=>'width:100%']) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true,'placeholder'=>'电话','style'=>'width:100%']) ?>
    
  
    
        <?= $form->field($model, 'address')->textInput(['maxlength' => true,'placeholder'=>'','style'=>'width:100%']) ?>
         <input type="hidden" id="df" value="">
   
  
    </ul>
    </div>
     <div class="col-lg-offset-7">

        <?= Html::button($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'j-btn']) ?>
         
    </div>
    <?php ActiveForm::end(); ?>


<?php
$requestUrl =yii\helpers\Url::toRoute($model->isNewRecord?['create']:['update','id'=>$model->id]);
$js = <<<JS
    commit('#j-btn','{$requestUrl}'); 
JS;

$this->registerJs($js);
    