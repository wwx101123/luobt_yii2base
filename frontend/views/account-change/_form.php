<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\AccountChange;
use common\widgets\JsBlock;
use common\models\Account;
/* @var $this yii\web\View */
/* @var $model common\models\AccountChange */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-change-form">
  <br/>
  <div class="row">
    
    <div class="col-md-1"></div>
    <div class="col-md-3">  
        <?= $accountModel->getAttributeLabel('account3');?>:
        <?= $account->account3;?>
    </div>

    <div class="col-md-3">
        <?= $accountModel->getAttributeLabel('account4');?>:
        <?= $account->account4;?>
    </div>

    <div class="col-md-3">
        <?= $accountModel->getAttributeLabel('account5');?>:
        <?= $account->account5;?>  
    </div>
  </div>
  <br/>
 
    
    <?php $form = ActiveForm::begin(['method'=>'post']);?> 

    <?= $form->field($model, 'type')->dropDownList(AccountChange::getTypes()) ?>

    <?= $form->field($model, 'money')->textInput(['maxlength' => true]) ?>

    <div class="col-lg-offset-6">
        <div class="form-group">
            <?= Html::submitButton('确认转换',['class' => 'btn btn-success'])?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php JsBlock::begin();?>
<script type="text/javascript">
  $('.btn-success').on('click', function(e){
  $val = $('#accountchange-type').val();
      e.preventDefault();
      if($val==2){
        layer.confirm('此积分只可用与复投！如强行转出将不可进入下期!',{
          btn:['是的','不']
        },function () {
             $('#w0').submit();
        });
      }else{

         layer.confirm('确定要转换吗',{
          btn:['是的','不']
        },function () {
             $('#w0').submit();
        });
      }
  });
</script>
<?php JsBlock::end();?>

 