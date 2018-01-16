<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '修改密码';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">

    <div class="row">
        <div class="col-lg-5">
            
            <?php if (Yii::$app->session->hasFlash('info')):?>
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">
                    &times;
                </a>
                <?php echo Yii::$app->session->getFlash('info');?>
            </div>
            <?php endif;?>

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'grade')->radioList(['1'=>yii::t('app','一级'), '2'=>yii::t('app','二级')])?>

                <?= $form->field($model, 'oldpassword')->passwordInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'newpassword')->passwordInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'conpassword')->passwordInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('确认修改', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
