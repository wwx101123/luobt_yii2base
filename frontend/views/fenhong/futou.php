<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Bankcard */

$this->title = '复投';
$this->params['breadcrumbs'][] = ['label' => 'Bankcards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-8 col-md-offset-1 form-horizontal">
    <?php $form = ActiveForm::begin(['action' => ['fenhong/futouac'],]); ?>

<!--     <div class="form-group">
        <label class="col-lg-3 control-label">推荐人数:</label>
            <div class="col-lg-3">
                <p class="form-control-static text-muted">123</p>
            </div>
    </div> -->

    <?php if($errorMsg):?>
        <div class="form-group">
            <label class="col-lg-3 control-label"></label>
                <div class="col-lg-3">
                    <p class="form-control-static text-muted text-danger"><?= $errorMsg ?></p>
                </div>
        </div>
    <?php else:?>
        <div class="form-group">
            <label class="col-lg-3 control-label"></label>
                <div class="col-lg-3">
                    <p class="form-control-static text-muted"><?= Html::submitButton('点击复投') ?></p>
                </div>
        </div>
    <?php endif;?>

    <?php ActiveForm::end(); ?>
</div>

