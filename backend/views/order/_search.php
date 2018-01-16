<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Order;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="">
    <!-- <div class="col-ld-3">
        <div ></div>
    </div> -->

    <div class="order-search">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'id'=>'order_form'
        ]); ?>

       
       <!--  <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 'pay_id')->dropDownList(Order::$pay_type_arr) ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'delivery')->dropDownList(Order::$delivery_arr) ?>
            </div>
        </div> -->
        <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 's_date')->widget(DateTimePicker::classname(), [ 
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
                ]); ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'e_date')->widget(DateTimePicker::classname(), [ 
                    'options' => ['placeholder' => ''], 

                    'pluginOptions' => [ 
                        'autoclose' => true, 
                        'todayHighlight' => true, 
                        // 'format' => 'yyyy-m-d',
                        'todayBtn' => true,
                    ] 
                ]); ?>
            </div>
        </div>
 <div class="row">
        <!--     <div class="col-lg-6">
                <?= $form->field($model, 'order_status')->dropDownList(Order::$status_arr) ?>
            </div> -->
            <div class="col-lg-6">
                <?= $form->field($model,'usename')->textInput()->label('会员编号') ?>
            </div>
        <div class="col-lg-6">
                <?= $form->field($model,'order_no')->textInput() ?>
            </div>


        </div>
   
        
    <div class="col-lg-offset-5">
    <div class="form-group">
            <?= Html::submitButton(Yii::t('app', '查询'), ['class' => 'btn btn-primary']) ?>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            <?= Html::Button(Yii::t('app', '导出订单'), ['class' => 'btn btn-info','id'=>'export']) ?>
     <!--        <?= Html::submitButton(Yii::t('app', '昨天订单'), ['class' => 'btn btn-primary']) ?>
            <?= Html::submitButton(Yii::t('app', '今天订单'), ['class' => 'btn btn-primary']) ?> -->
            <!-- <?= Html::Button(Yii::t('app', '发货'), ['class' => 'btn btn-info','id'=>'delivery']) ?> -->

        </div>

        <?php ActiveForm::end(); ?>

    </div>
    </div>

</div>

<?php 
$url = yii\helpers\Url::to(['batch-delivery']);
$export_url = yii\helpers\Url::to(['order/export']);

$js =<<<JS

      $('#delivery').on('click', function ()
      {     
         var form = $('#order_form');
         $.ajax({
                url:'$url',
                data:$('#order_form').serialize(),
                type:'post',
                success:function (data)
                {
                    if (data.code) {
                        layer.msg(data.msg,{
                            icon:1
                        },function ()
                        {
                            window.location.reload();
                        })
                    }else{
                        layer.msg(data.msg,{
                            icon:2
                        });
                        $.each(data.data,function (i,v)
                        {
                            $('#ordersearch-'+i).val(v);
                        });
                    }
                }
          })

      })
         
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



