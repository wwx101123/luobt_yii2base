<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderGoods */

$this->title = Yii::t('app', 'Create Order Goods');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Order Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-goods-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
