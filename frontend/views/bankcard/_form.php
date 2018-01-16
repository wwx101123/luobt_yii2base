<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Region;

/* @var $this yii\web\View */
/* @var $model common\models\Bankcard */
/* @var $form yii\widgets\ActiveForm */
$url=\yii\helpers\Url::toRoute(['get-region']);
?>
<div class="col-md-8 col-md-offset-1 form-horizontal">

<div class="bankcard-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bankname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'province')->widget(\chenkby\region\Region::className(),[
            'model'=>$model,
            'url'=>$url,
            'province'=>[
                'attribute'=>'province',
                'items'=>Region::getRegion(),
                'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择省份']
            ],
            'city'=>[
                'attribute'=>'city',
                'items'=>Region::getRegion($model['province']),
                'options'=>['class'=>'form-control form-control-inline','style'=>'margin-top:10px;margin-bottom:10px','prompt'=>'选择城市']
            ],
            // 'district'=>[
            //     'attribute'=>'district',
            //     'items'=>Region::getRegion($model['city']),
            //     'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择县/区']
            // ]
            // 

    ])->label('开户地址');?>
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
