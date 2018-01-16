<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap\Modal;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="cbp-spmenu-push">

<!-- <div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right accordion2" id="cbp-spmenu-s2"> -->
<?php $this->beginBody() ?>
    <?= $content ?>
<?php
    Modal::begin([
        'id' => 'page-modal',
        'header' => '<h4 class="modal-title"></h4>',
        //'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
        'options' => [
            'tabindex' => false
        ],
    ]); 
    Modal::end();
?>
<?php $this->endBody() ?>

<!-- <div class="footer">  
    <p>会员管理系统  版权所有</p>
</div> -->

</body>
</html>
<?php $this->endPage() ?>
