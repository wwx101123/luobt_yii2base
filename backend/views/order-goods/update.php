<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderGoods */

$this->title = Yii::t('app', '修改订单产品： '.$model->goods_name, [
    'modelClass' => 'Order Goods',
]) ;

?>
<div class="order-goods-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
