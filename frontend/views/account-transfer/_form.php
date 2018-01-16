<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\AccountTransfer;
use common\widgets\JsBlock;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\AccountTransfer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-transfer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList(AccountTransfer::getTypes()) ?>
    

    <?= $form->field($model, 'into_name')->textInput() ?>
    <div class="form-group hidden" id='fathername-div'>
        <label class="control-label">用户名</label>
                <p class="form-control-static text-muted text-danger" id='fathername-p'></p>
    </div>
    <?= $form->field($model, 'out_money')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'info')->textarea(['rows' => 3]) ?>


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
      layer.confirm('确定要转帐吗',{
        btn:['是的','不']
      },function () {
           $('#w0').submit();
      });
  });



 $("#accounttransfer-into_name").on('blur', function(){
        var val = $(this).val();
        var url = "<?= Url::to(['get-rename'])?>";
        $.post(url,{name:val},function(data){
            $("#fathername-div").removeClass('hidden');
            $("#fathername-p").html(data.msg);
            if (data.status == 1) {
                $("#fathername-p").addClass('text-success');
                $("#fathername-p").removeClass('text-danger');
            }
            else {
                $("#fathername-p").removeClass('text-success');
                $("#fathername-p").addClass('text-danger');
            }
        });
    });

</script>

<?php 
JsBlock::end();

 ?>
