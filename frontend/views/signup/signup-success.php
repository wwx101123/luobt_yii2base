<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Parameter;
use yii\helpers\Url;
$this->title = '会员注册成功';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="col-md-8 col-md-offset-1 form-horizontal">

    <!-- <div class="row"> -->
        <!-- <div class="col-lg-5"> -->
        <div class="form-group">
        <label class="control-label" for="">会员编号:</label>
        <?= $model->username;?>
        </div>
        <div class="form-group">
        <label class="control-label" for="">注册金额:</label>
        <?= $model->cpzj;?>
        </div>
        <!-- </div> -->
    <!-- </div> -->
    </div>
          
    <div class="success_order_box">
  <p>现在您还可以：<font>
  <?php if ($is_agent==1): ?>
    <a href="<?php echo Url::to(['agent/unactivate']) ?>">去开通</a>
  <?php endif ?>
  <a href="<?php echo Url::to(['signup/index']) ?>">继续注册</a></font></p>
  
  </div>

</div>
</div>
