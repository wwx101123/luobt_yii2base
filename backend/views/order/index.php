<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;
use common\models\Member;
use common\widgets\JsBlock;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '订单列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
  
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
          'pager' => [
        'firstPageLabel' => '第一页',
        'lastPageLabel' => '最后一页',
        ],
        "options" => ["style"=>"overflow:auto", "id" => "grid"],
        'columns' => [
            [
            'class' => 'yii\grid\CheckboxColumn',
            'name' => 'id',
            ],
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

            [
                'attribute'=>'name',
                'format'=>'raw',
                'value'=>function ($data)
                {
                    return $data->name;
                }
            ],
            'address',
            // 'postcode',
            'tel',
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
                'template'=>'{view}'
            ],
        ],
    ]); ?>
</div>
    <?= Html::a('批量确认订单', "javascript:void(0);", ['class' => 'btn btn-success gridview','id'=>'audi']) ?>

<?php JsBlock::begin(); ?>
<script type="text/javascript">

$(document).on('click', '#audi', function () {
    var key = $("#grid").yiiGridView("getSelectedRows");
    var url = '<?=  Url::to(['order/refuse']) ?>';
layer.confirm('是否要批量确认', {
  btn: ['是','否'] //按钮
}, 
function(){
 $.post(url,{id:key},function(data){
      if (data.code) {
        layer.msg(data.msg,{icon:1},function(){
          window.location.reload();
        },'json');
      }else{
        layer.msg(data.msg,{icon:2});
      }
      console.log(data);
    }); 
});
});

</script>
<?php JsBlock::end();  ?>   