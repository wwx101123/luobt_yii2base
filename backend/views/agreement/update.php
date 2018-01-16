<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = Yii::t('app', '注册协议');
$this->params['breadcrumbs'][] = Yii::t('app', '修改');
?>
<div class="product-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
