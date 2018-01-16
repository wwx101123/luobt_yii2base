<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = Yii::t('app', '修改订单: ', [
    'modelClass' => 'Order',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '订单管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '订单：'.$model->order_no, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', '修改');
?>
<div class="order-update">

    <!-- <div class="col-lg-7"> -->
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    <!-- </div> -->
    <!-- <div class="col-lg-5"> -->
    <!-- </div> -->
</div>
