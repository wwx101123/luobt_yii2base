<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Parameter;

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
</div>
</div>
