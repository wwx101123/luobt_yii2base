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
    <p>
        <?= Html::a(Yii::t('app', '返回'), ['index'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="order-search">

        <?php $form = ActiveForm::begin([
            'action' => ['complete'],
            'method' => 'get',
            'id'=>'order_form'
        ]); ?>

       <!--  <div class="row">
              <div class="col-lg-6">
                <?= $form->field($model, 'order_status')->dropDownList(Order::$status_arr) ?>
            </div>
             <?= $form->field($model, 'order_status')->hiddenInput()->label(false) ?>
             <div class="col-lg-6">
                <?= $form->field($model, 'pay_id')->dropDownList(Order::$pay_type_arr) ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'pay_status')->dropDownList(Order::$pay_arr) ?>
            </div>
        </div> -->
        <div class="row">
           
          <!--   <div class="col-lg-6">
                <?= $form->field($model, 'delivery')->dropDownList(Order::$delivery_arr) ?>
            </div> -->
        </div>
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
            <div class="col-lg-4">
                <?= $form->field($model,'order_no')->textInput() ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model,'usename')->textInput()->label('会员编号') ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'order_status')->dropDownList(Order::$status_array) ?>
            </div>

        </div>
        
    <div class="col-lg-offset-5">
    <div class="form-group">
            <?= Html::submitButton(Yii::t('app', '查询'), ['class' => 'btn btn-primary']) ?>
           <?= Html::Button(Yii::t('app', '导出订单'), ['class' => 'btn btn-info','id'=>'export']) ?>
            <!-- <?= Html::Button(Yii::t('app', '导出配货信息'), ['class' => 'btn btn-info','id'=>'delivery']) ?> -->
     <!--        <?= Html::Button(Yii::t('app', '批量完成'), ['class' => 'btn btn-info','id'=>'complete']) ?>
            <?= Html::Button(Yii::t('app', '批量收款'), ['class' => 'btn btn-info','id'=>'receipt']) ?> -->

        </div>

        <?php ActiveForm::end(); ?>

    </div>
    </div>

</div>

<?php 
$complete_url = yii\helpers\Url::to(['order/batch-complete']);
$receipt_url = yii\helpers\Url::to(['order/batch-receipt']);
$export_url = yii\helpers\Url::to(['order/export']);
$js =<<<JS

      $('#complete').on('click', function ()
      {     
         var form = $('#order_form');
         $.ajax({
                url:'$complete_url',
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
                },
                error:function ()
                {

                }
          })

      });
      $('#receipt').on('click', function ()
      {     
         var form = $('#order_form');
         $.ajax({
                url:'$receipt_url',
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
                        })
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



