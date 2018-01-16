<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Sort;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '产品列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '添加产品'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'goods_name',
            'goods_code',
            [
                'attribute'=>'cate_id',
                'value' =>function($data){
                    return Sort::getName($data->cate_id);
                },
            ],
            
            // 'goods_img',
            'inventory',
            // 'content:ntext',
            'market_price',
            'present_price',
            
            // 'is_show',
            // 'is_top',
            // 'is_hot',
            // 'is_new',
            // 'is_reg',
            // 'create_time:datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
