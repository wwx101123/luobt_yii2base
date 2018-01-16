<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use common\models\ToCash;
/* @var $this yii\web\View */
/* @var $model common\models\ToCashSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="to-cash-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>    
    <div class="row">
        <div class="col-lg-6">
                <?= $form->field($model, 's_time')->widget(DateTimePicker::classname(), [ 
                'options' => ['placeholder' => ''], 

                'pluginOptions' => [ 
                    'autoclose' => true, 
                    'todayHighlight' => true, 
                    // 'format' => 'yyyy-m-d',
                    'todayBtn' => true,
                    // 'showMeridian' => true,
                    //'daysOfWeekDisabled' => '0,6',/*禁用星期天星期六*/
                    //'hoursDisabled' => '0,1,2,3,4,5,6,7,8,19,20,21,22'/*禁用小时*/
                ] 
                ])->label('开始时间'); ?>
        </div>
    <div class="col-lg-6">
                <?= $form->field($model, 'e_time')->widget(DateTimePicker::classname(), [ 
                    'options' => ['placeholder' => ''], 

                    'pluginOptions' => [ 
                        'autoclose' => true, 
                        'todayHighlight' => true, 
                        // 'format' => 'yyyy-m-d',
                        'todayBtn' => true,
                    ] 
                ])->label('结束时间'); ?>
            </div>
        </div>
        
    <div class="col-lg-offset-5">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', '查询'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', '重置'), ['class' => 'btn btn-default']) ?>
    </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>


