<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\MemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="member-search">

    <?php $form = ActiveForm::begin([
        'action' => 'index',
        'method' => 'get',
        'id' => 'order_form',
    ]); ?>

    <div class="row">
    <div class="col-lg-4">
        
    <?= $form->field($model, 'username')->textInput(['placeholder'=>"输入需要搜索的会员编号或姓名"]) ?>
    </div>
    </div>
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
                ])->label('开始激活时间'); ?>
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
                ])->label('结束激活时间'); ?>
            </div>
        </div>
    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'is_lock') ?>

    <?php // echo $form->field($model, 'shop_id') ?>

    <?php // echo $form->field($model, 'activate') ?>

    <?php // echo $form->field($model, 'u_level') ?>

    <?php // echo $form->field($model, 'dan') ?>

    <?php // echo $form->field($model, 'is_agent') ?>

    <?php // echo $form->field($model, 'cpzj') ?>

    <?php // echo $form->field($model, 'g_level') ?>

    <div class="form-group">
        <?= Html::submitButton('查找', ['class' => 'btn btn-primary']) ?>
        <?= Html::Button(Yii::t('app', '导出EXCEL'), ['class' => 'btn btn-info','id'=>'export']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
$export_url = yii\helpers\Url::to(['export']);


$js =<<<JS

       $('#export').on('click', function ()
      {    
         var form = $('#order_form');
         var old_url = form.attr("action");
        form.attr("action",'$export_url'); 
        form.submit();
        setTimeout(function ()
        {
            form.attr("action",old_url); 
        },200)
       

      })
         
      
JS;
$this->registerJs($js);


 ?>
