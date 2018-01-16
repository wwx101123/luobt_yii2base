<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Bankcard;
use common\models\Account;
use common\models\ToCash;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $model common\models\ToCash */
/* @var $form yii\widgets\ActiveForm */
$this->registerCSSFile("@web/statics/css/bankcard.css?v=".time());


?>

 <?php $form = ActiveForm::begin([
    'action' => ['to-cash/index'],
  ]); ?>

  <div class="card">
    <?= $form->field($model, 'bankcard_id')->radioList(Bankcard::getBankData(),
  [
      'item' => function($index, $label, $name, $checked, $value) {
          $id = $name . $value;
          $infoArr = explode("|", $label);
          $checked=$checked ? "checked":"";
          $return = '<div  class="col-md-3"><label for="' . $id . '" class="center-block">';
          $return .= '<div class="well well-lg '.$checked.'">';
          $return .= '<i></i>';
          $return .= '<h2>'.$infoArr[0].'</h2>';
          $return .= '<span class="label label-danger">银行卡</span>';
          $return .= '<span class="lead">'.$infoArr[1].'</span>';
          $return .= '<span class="lead">'.$infoArr[2].'</span>';
          $return .= '</div>';
          $return .= '<input style="display:none;" type="radio" id="'.$name.$value.'" name="' . $name . '" value="'.$value.'" class="md-radiobtn"  '.$checked.'>';
          $return .= '</label></div>';
          return $return;
      }
  ])->label(false)?>
  </div>
 <div class="col-lg-6 col-md-offset-2">
    <?= $form->field($account_model, 'account3')->textInput(['readonly' => true]);?>

    <?= $form->field($model, 'type')->dropDownList(ToCash::getToList()) ?>

    <?= $form->field($model, 'to_money')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '确定') : Yii::t('app', '修改'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>
   
 </div>

<?php ActiveForm::end(); ?>


<?php 
JsBlock::begin();

 ?>
<script type="text/javascript">
  
  $('.btn-success').on('click', function(e){
      e.preventDefault();
      layer.confirm('确定要提现吗',{
        btn:['是的','不']
      },function () {
           $('#w0').submit();
      });
  });

  $('.card .well').click(function(){
      $(this).addClass('checked');
      
      $('.card .well').not(this).removeClass('checked');
  });


</script>

<?php 
JsBlock::end();

 ?>
