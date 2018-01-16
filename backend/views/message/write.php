<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
// use frontend\models\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '发邮件';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- <div class="message-index col-lg-offset-1 col-lg-8"> -->
    <div class="form_padding">

    <?php $form =  ActiveForm::begin();?>
    <div class="input-box">
      <div class="row">
        <div class="col-lg-5">      
    <?php echo $form->field($model, 'tusername')->textInput() ?>
    <?php echo $form->field($model, 'title')->textInput() ?>
    <?php echo $form->field($model, 'content')->textArea() ?>

    <!-- <div class="form-group text-center"> -->
            <?= Html::submitButton('提交', ['class' => 'btn btn-success']) ?>
        <!-- </div> -->
    	</div>
       </div>
    </div>
    <?php $form::end() ?>
<!-- </div> -->
</div>