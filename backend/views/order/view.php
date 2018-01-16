<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Member;
use common\models\Order;
use common\models\PickUpPoints;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = '订单：'.$model->order_no;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '订单管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th colspan="2">订单信息</th>
            
        </tr>
    </thead>
    <tbody>
        <tr>
            <td width="30%">订单号： <?php echo $model->order_no ?></td>
            <td>会员编号: <?php echo Member::getName($model->user_id)?></td>
        </tr>
        <tr>
            <td>下单时间： <?php echo date('Y-m-d H:i:s',$model->create_time) ?></td>
            <td>订单状态: <?php echo Order::$status_arr[$model->order_status] ?></td>
        </tr>
        <tr>
            <td>订单总金额（含运费）： <?php echo $model->order_amount ?></td>
            <td></td>
        </tr>

        <tr>
            <td>发货状态： <?php echo Order::$delivery_arr[$model->delivery] ?></td>
            <td></td>
        </tr>
        <?php if ($model->pay_status==0): ?>
            
        <tr data-key="<?php echo $model->id ?>">
            <td colspan="2"><button class="btn btn-primary" data-toggle='modal'  data-target = '#page-modal' id="order_update">修改</button></td>
        </tr>
        <?php endif ?>
        
    </tbody>
    <thead>
        <tr>
            <th colspan="2">配送信息</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>收货人姓名： </td>
            <td><?php echo $model->name ?></td>
        </tr>
        <tr>
            <td>收货人地址： </td>
            <td><?php echo $model->address ?></td>
        </tr>
        <tr>
            <td>联系电话： </td>
            <td><?php echo $model->tel ?></td>
        </tr>
       
    </tbody>

</table>
<style type="text/css">
    .goods tbody tr td{
       line-height: 38px;
    }
    
</style>
<table class="table table-striped table-bordered goods"> 
    <thead>
      
        <tr>
            <th>商品</th>
            <th>规格</th>
            <th>价格</th>
            <th>数量</th>
            <th>总价</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($goods_list as $k => $v): ?>
            
       
        <tr data-key="<?php echo $v['id'] ?>">
            <td><img src="<?php echo $v['goods_img'] ?>" height="38px"> <?php echo $v['goods_name'] ?></td>
            <td>1</td>
            <!-- <td><?php echo $v['present_price'].'(购物积分)+'.$v['account3_price'].'(消费积分)+'.$v['account5_price'].'(股权积分)' ?> </td> -->
            <td><?php echo $v['present_price']?></td>
            <td><?php echo $v['buy_number'] ?> </td>
           <!--  <td><?php echo $v['buy_number']*($v['present_price']+$v['account3_price']+$v['account5_price']) ?></td> -->
           <td><?php echo $v['present_price'] * $v['buy_number']?></td>
            <td> 
                <button class="btn btn-primary" data-toggle='modal'  data-target = '#page-modal' id="update">修改</button>
            </td>
        </tr>
         <?php endforeach ?>
    </tbody>
</table>

<table class="table table-striped table-bordered "> 
    <thead>  
        <tr>
            <th>操作时间</th>
            <th>操作人</th>
            <th>订单状态</th>
            <th>发货状态</th>
            <!-- <th>支付状态</th> -->
            <th>备注</th>
            
        </tr>
    </thead>
    <tbody>
        <?php foreach ($action_list as $key => $var): ?>
            
       
        <tr>
            <td><?php echo date('Y-m-d H:i:s',$var['log_time']) ?></td>
            <td><?php echo $var['action_user'] ?></td>
            <td><?php echo Order::$status_arr[$var['order_status']] ?></td>
            <td><?php echo Order::$delivery_arr[$var['shipping_status']] ?></td>
            <!-- <td><?php echo Order::$pay_arr[$var['pay_status']] ?></td> -->
            <td><?php echo $var['action_note'] ?></td>
           
        </tr>
         <?php endforeach ?>
    </tbody>
</table>
<?php if ($model->order_status!=2&&$model->order_status!=3): ?>
    
<?php $form = ActiveForm::begin([
        'action'=>['handle'],
        'method'=>'post'
    ]); ?>
<div class="col-lg-12">
  <textarea cols="50" name="bz" rows="10"></textarea>
</div>
<div class="col-lg-12">
    
    <?php if ($model->order_status == 0): ?>

        <?php echo Html::submitButton('确认订单',['name'=>'common','value'=>'activeOrder','class'=>'btn btn-success']); ?>
        <?php echo Html::submitButton('取消订单',['name'=>'common','value'=>'cancel','class'=>'btn btn-danger']); ?>
    <?php endif ?>
   
    <?php if ($model->order_status == 1): ?> 
            <?php if ($model->delivery == 0): ?>
                <?php echo Html::submitButton('发货',['name'=>'common','value'=>'delivery','class'=>'btn btn-success']); ?>
            <?php else: ?>
                <?php echo Html::submitButton('完成订单',['name'=>'common','value'=>'complete','class'=>'btn btn-success']); ?>
                <?php echo Html::submitButton('取消发货',['name'=>'common','value'=>'noDelivery','class'=>'btn btn-danger']); ?>    
            <?php endif ?> 
    <?php endif ?>

    <?php if ($model->order_status == 4): ?>
        <!-- <?php echo Html::submitButton('退款',['name'=>'common','value'=>'refund','class'=>'btn btn-danger']); ?> -->
    <?php endif ?>

    <input type="hidden" name="id" value="<?php echo $id ?>">
  
</div>
<?php ActiveForm::end(); ?>
<?php endif ?>

<?php

//模态提交代码
$updateUrl = yii\helpers\Url::toRoute(['order-goods/update']);
$order_updateUrl = yii\helpers\Url::toRoute(['order/update']);
$updatePayIdUrl = yii\helpers\Url::toRoute(['order/update-pay-id']);
$js = <<<JS
    fn('#order_update','{$order_updateUrl}', '', '编辑');
    
    fn('#update','{$updateUrl}', '', '修改');
    fn('#updatePayIdUrl','{$updatePayIdUrl}', '', '修改');
JS;
$this->registerJs($js);
?>


