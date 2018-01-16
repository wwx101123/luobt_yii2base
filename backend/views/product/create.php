<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = Yii::t('app', '添加产品');
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">

<div class="row">
    <div class="col-lg-7">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
    <div class="col-lg-5">

    </div>
</div>
</div>
