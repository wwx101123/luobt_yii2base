<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Parameter;
use common\models\Relationship;

$this->title = '账户信息';
?>
<div class="member-info">
    <div class="col-md-8 col-md-offset-1 form-horizontal">

    <div class="form-group">
        <label class="col-lg-3 control-label"><?= $member->getAttributeLabel('username');?>:</label>
            <div class="col-lg-3">
                <p class="form-control-static text-muted"><?= Html::encode($member->username);?></p>
            </div>
    </div>

    <div class="form-group">
        <label class="col-lg-3 control-label"><?= $member->relationship->getAttributeLabel('re_nums');?>:</label>
            <div class="col-lg-3">
                <p class="form-control-static text-muted"><?= Html::encode($member->relationship->re_nums);?></p>
            </div>
    </div>

    <div class="form-group">
        <label class="col-lg-3 control-label"><?= $member->getAttributeLabel('u_level');?>:</label>
            <div class="col-lg-3">
                <p class="form-control-static text-muted"><?= Parameter::getUlevelName($member->u_level);?></p>
            </div>
    </div>

    <div class="form-group">
        <label class="col-lg-3 control-label"><?= $member->account->getAttributeLabel('account2')?>:</label>
            <div class="col-lg-3">
                <p class="form-control-static text-muted"><?= Html::encode($member->account->account2);?></p>
            </div>
    </div>

    <div class="form-group">
        <label class="col-lg-3 control-label"><?= $member->account->getAttributeLabel('account3')?>:</label>
            <div class="col-lg-3">
                <p class="form-control-static text-muted"><?= Html::encode($member->account->account3);?></p>
            </div>
    </div>

    <div class="form-group">
        <label class="col-lg-3 control-label"><?= $member->account->getAttributeLabel('account4')?>:</label>
            <div class="col-lg-3">
                <p class="form-control-static text-muted"><?= Html::encode($member->account->account4);?></p>
            </div>
    </div>

    <div class="form-group">
        <label class="col-lg-3 control-label"><?= $member->account->getAttributeLabel('account5')?>:</label>
            <div class="col-lg-3">
                <p class="form-control-static text-muted"><?= Html::encode($member->account->account5);?></p>
            </div>
    </div>
    
</div>

<div class="clear"></div>

</div>
