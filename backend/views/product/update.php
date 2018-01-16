<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = Yii::t('app', '更新 {modelClass}: ', [
    'modelClass' => '产品',
]) . $model->goods_name;
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => ['index']];

$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];

$this->params['breadcrumbs'][] = Yii::t('app', '更新');
?>
<div class="product-update">

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
