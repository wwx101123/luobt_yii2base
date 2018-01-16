<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;
use common\models\Member;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '发货单列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <?php  echo $this->render('_complete_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
          'pager' => [
        'firstPageLabel' => '第一页',
        'lastPageLabel' => '最后一页',
        ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'order_no',
            [
                'attribute'=>'user_id',
                'value'=>function ($data)
                {
                    return Member::getName($data->user_id);
                }
            ],
            // 'shipping_status',
            // 'pay_status',
            'name',
            // 'address',
            // 'postcode',
            // 'tel',
            // 'shipping_id',
            // 'shipping_name',
            // 'pay_id',
            // 'pay_name',
            'goods_amount',
            // 'shipping_fee',
            'order_amount',
            // 'money_paid',
            'create_time:datetime',
            // 'confirm_time:datetime',
            // 'goods_img',
            [
                'attribute'=>'order_status',
                'filter'=>Html::activeDropDownList($searchModel,'order_status',Order::$status_arr,['class'=>'form-control']),
                'value'=>function ($data)
                {
                    return Order::$status_arr[$data->order_status];
                }
            ],
            // [
            //     'attribute'=>'pay_status',
            //     'filter'=>Html::activeDropDownList($searchModel,'pay_status',Order::$pay_arr,['class'=>'form-control']),
            //     'value'=>function ($data)
            //     {
            //         return Order::$pay_arr[$data->pay_status];
            //     }
            // ],
            [
                'attribute'=>'delivery',
                'filter'=>Html::activeDropDownList($searchModel,'delivery',Order::$delivery_arr,['class'=>'form-control']),
                'value'=>function ($data)
                {
                    return Order::$delivery_arr[$data->delivery];
                }
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['order/view','id'=>$model->id]);
                    }
                 ],

            ],
        ],
    ]); ?>
</div>
