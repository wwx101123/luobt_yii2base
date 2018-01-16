<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = '登录';

$fieldOptions1 = [
    'inputTemplate' => "{input}"
];

$fieldOptions2 = [
    'inputTemplate' => "{input}"
];

?>
<div class="top">
    <div class="wraper">
        <div class="logo"><a href="#"><?= Html::img('@web/statics/images/logo.png');?></a></div>
        <div class="clear"></div>
    </div>
</div>
<div class="login_wraper">
    <div class="login_bg">
        <div class="tablogin">
            <div class="tabt">
                <span class="current">登录</span>
                <div class="clear"></div>
            </div>
            <div class="t1" style="display:block">
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
                <p class="name_p">用户名</p>

            <?= $form
                ->field($model, 'username', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => '请输入用户名', 'class' => 'name_input']) ?>

            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' =>  '请输入登录密码', 'class' => 'name_input']) ?>

            <div class="row">
                <div class="col-xs-8">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
                <?= Html::submitButton('登录', ['class' => 'login_btn']) ?>
            </div>
            <?php ActiveForm::end(); ?>

                <!-- <p class="forget_psd"><a href="#">忘记密码？</a></p> -->
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="footer_login">
    <p><?= Yii::$app->name;?> 2017 版权所有</p>
</div>
