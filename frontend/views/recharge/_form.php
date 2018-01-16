<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Recharge;
use common\widgets\JsBlock;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Recharge */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recharge-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(Recharge::getReList()) ?>

    <?= $form->field($model, 're_money')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'pay_type')->dropDownList(Recharge::$pay_type,['prompt'=>'请选择支付类型']) ?>
     <?= $form->field($model, 'pay_time')->widget(DateTimePicker::classname(), [ 
                'options' => ['placeholder' => ''], 

                'pluginOptions' => [ 
                    'autoclose' => true, 
                    'todayHighlight' => true, 
                    // 'format' => 'yyyy-mm-dd hh:ii:ss',
                    'todayBtn' => true,
                    // 'showMeridian' => true,
                    //'daysOfWeekDisabled' => '0,6',/*禁用星期天星期六*/
                    //'hoursDisabled' => '0,1,2,3,4,5,6,7,8,19,20,21,22'/*禁用小时*/
                ] 
                ]); ?>

    <?= $form->field($model, 'info')->textarea(['rows'=>3]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '确定') : Yii::t('app', '修改'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php 
JsBlock::begin();

 ?>
<script type="text/javascript">
  
  $('.btn-success').on('click', function(e){
      e.preventDefault();
      layer.confirm('确定要充值吗',{
        btn:['是的','不']
      },function () {
           $('#w0').submit();
      });
  });


</script>

<?php 
JsBlock::end();

 ?>