<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '产品中心'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">


    <p>
        <?= Html::a(Yii::t('app', '修改'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', '删除'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'goods_name',
            'goods_code',
            'cate_id',
            'big_id',
            'goods_img',
            'inventory',
            'content:ntext',
            'market_price',
            'present_price',
            'account3_price',
            'account5_price',
            [
                'attribute'=>'is_show',
                'value' =>$model->is_show==0?'否':'是',
            ],            [
                'attribute'=>'is_top',
                'value' =>$model->is_top==0?'否':'是',
            ],            [
                'attribute'=>'is_hot',
                'value' =>$model->is_hot==0?'否':'是',
            ],            [
                'attribute'=>'is_new',
                'value' =>$model->is_new==0?'否':'是',
            ],            [
                'attribute'=>'is_reg',
                'value' =>$model->is_reg==0?'否':'是',
            ],            [
                'attribute'=>'is_login',
                'value' =>$model->is_login==0?'否':'是',
            ],
            'create_time:datetime',
        ],
    ]) ?>

</div>
