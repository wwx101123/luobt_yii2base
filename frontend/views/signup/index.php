<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\models\Parameter;
use common\models\Product;
use yii\helpers\ArrayHelper;
use common\widgets\JsBlock;

$this->title = '登记注册';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="col-md-8 col-md-offset-1 form-horizontal">

    <!-- <div class="row"> -->
        <!-- <div class="col-lg-5"> -->
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'shop_name')->textInput() ?>
                <?= $form->field($model, 'username')->textInput() ?>
                <?= $form->field($model, 're_name')->textInput() ?>

                <div class="form-group hidden" id='rename-div'>
                    <label class="control-label">推荐人姓名</label>
                            <p class="form-control-static text-muted text-danger" id='rename-p'></p>
                </div>

                <?= $form->field($model, 'father_name')->textInput() ?>
                <div class="form-group hidden" id='fathername-div'>
                    <label class="control-label">接点人姓名</label>
                            <p class="form-control-static text-muted text-danger" id='fathername-p'></p>
                </div>
                <?= $form->field($model, 'area')->dropDownList(Parameter::getArea()) ?>

                <?php //echo $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'password_hash_confirm')->passwordInput() ?>                
                <?= $form->field($model, 'password_two')->passwordInput() ?>
                <?= $form->field($model, 'password_hash_two_confirm')->passwordInput() ?>
                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'code')->textInput() ?>
                <?= $form->field($model, 'phone')->textInput() ?>
                <?= $form->field($model, 'address')->textInput() ?>
                <?= $form->field($model, 'address_name')->textInput() ?>
                <?= $form->field($model, 'address_tel')->textInput() ?>
                <?= $form->field($model, 'u_level')->dropDownList(Parameter::getUlevel()) ?>

                <?= $form->field($model, 'goods_id')->radioList(Product::getRegGoods(Product::IS_REG,0),
                    [
                        'item'=>function($index, $label, $name, $checked, $value){
                        $checked=$checked?"checked":"";
                        return '<label><input '.$checked.' type="radio" name="'.$name.'" value="'.$value.'">'.'<img src="'.Product::getImg($value).'" style="width:50px; height:40px" alt=""></br>'.$label.'</br>'.Product::getPrice($value).'</label>'.'&nbsp&nbsp&nbsp';
                    },
                    'itemOptions'=>['class'=>'myClass']])->label(''); 
                ?> 
                
                <div class="form-group">
                    <?= Html::submitButton('提交注册', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        <!-- </div> -->
    <!-- </div> -->

    </div>
    <div class="clear"></div>
</div>
</div>
<?php JsBlock::begin();?>
<script type="text/javascript">
    $("#signupform-re_name").on('blur', function(){
        var val = $(this).val();
        var url = "<?= Url::to(['get-rename'])?>";
        $.post(url,{name:val},function(data){
            $("#rename-div").removeClass('hidden');
            $("#rename-p").html(data.msg);
            if (data.status == 1) {
                $("#rename-p").addClass('text-success');
                $("#rename-p").removeClass('text-danger');
            }
            else {
                $("#rename-p").removeClass('text-success');
                $("#rename-p").addClass('text-danger');
            }
        });
    });

    $("#signupform-father_name").on('blur', function(){
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

$('#signupform-u_level').change(function(){
    var url = "<?= Url::to(['signup/get-reg'])?>";
    $.post(url,{level:$(this).val()},function(data){
        var li = '';
        $.each(data.data,function(i,v){
          if (i==0) {
            checked = 'checked';
          }else{
            checked = '';
          }
          li+='<label><input '+checked+' type="radio" name="SignupForm[goods_id]" value="'+v.id+'">'+'<img src="'+v.goods_img+'" style="width:50px; height:40px" alt=""></br>'+v.goods_name+'</br>'+v.present_price+'</label>'+'&nbsp;&nbsp;&nbsp;&nbsp;';
        });
        // console.log(li);
        $('#signupform-goods_id').html(li);       
    });
})

    // function agree(){
    //     layer.open({
    //       type: 1,
    //       area: ['600px', '360px'],
    //       shadeClose: true, //点击遮罩关闭
    //       title: '注册协议',
    //       content: '<?php echo $agreement?>'
    //     });
    // }
    
</script>
<?php JsBlock::end();?>
