<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Order Goods');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-goods-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Order Goods'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_id',
            'goods_id',
            'goods_name',
            'goods_sn',
            // 'goods_number',
            // 'market_price',
            // 'goods_price',
            // 'goods_attr:ntext',
            // 'goods_img',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
