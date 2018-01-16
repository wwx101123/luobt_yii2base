<?php 
$this->title = '注册信息';
$this->params['breadcrumbs'][] = $this->title;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Parameter;
use frontend\models\SignupForm;
use yii\widgets\ActiveForm;
use common\models\Member;
use common\models\Product;
 ?>
                      
 <style type="text/css">
   .detail-view th{ text-align: right;width: 30% }
 </style>                     
                      
    <div class="row">
      <div class="col-lg-3"></div>
        <div class='col-lg-6'>
          <?= DetailView::widget([
          'model' => $model,
          'attributes' => [
      			'username',
      			'shop_name',
      			[
      				'attribute'=>'u_level',
      				'value' =>Parameter::getUlevelName($model->u_level),	
      			],
      			'father_name',
      			[
      				'label'=>'接点人姓名',
      				'value'=>Member::getFatherName($model->father_name),		
      			],
      			're_name',
      			[
      				'label'=>'推荐人姓名',
      				'value'=>Member::getFatherName($model->re_name),	
      			],
      			[
      				'attribute'=>'area',
      				'value' =>SignupForm::$arr[$model->area],	
      			],
      			'name',
      			'code',
      			'phone',
      			'address_name',
      			'address',
      			'address_tel',
            // [
            //   'attribute'=>'goods_id',
            //   'value'=>Product::GetName($model->goods_id),
            // ],
          ],
        ])?>
      </div>
    </div>

    <?php $form = ActiveForm::begin([
          'method'=>'post',
          'action'=>['get-inform'],])?>
        <?= $form->field($model, 'shop_name')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'username')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'u_level')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 're_name')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'father_name')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'area')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'password')->hiddenInput(['maxlength' => true])->label(false) ?>
        <?= $form->field($model, 'password_hash_confirm')->hiddenInput(['maxlength' => true])->label(false) ?>
        <?= $form->field($model, 'name')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'code')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'phone')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'address')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'address_name')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'address_tel')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'email')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'password_two')->hiddenInput()->label(false)?>
        <?= $form->field($model, 'password_hash_two_confirm')->hiddenInput()->label(false) ?>

        <input type="hidden" name="SignupForm[goods_id][]" value=<?=$model->goods_id ?> >

        <?= $form->field($model, 'agreement')->hiddenInput()->label(false) ?>
        
        <div class="col-lg-offset-5">
          <div class="form-group">
              <?= Html::submitButton('立即注册',['class' =>'btn btn-success']) ?>
              <input class="btn btn-info" type="button" name="back" value="返回修改" onclick="javascript:window.history.go(-1);"/>
          </div>
        </div>
    <?php ActiveForm::end(); ?>


  