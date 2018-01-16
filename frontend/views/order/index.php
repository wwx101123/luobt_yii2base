 <?php 
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\models\OrderGoods;
use yii\widgets\LinkPager;
$this->title = '我的订单';
$this->params['breadcrumbs'][] = ['label'=>'购物商城','url'=>['product/index']];
$this->params['breadcrumbs'][] = $this->title;

  ?>

<div class="cart_box">              
                 
  <div class="data_box">

      <?php if (Yii::$app->session->hasFlash('info')):?>
          <div class="alert alert-success">
              <a href="#" class="close" data-dismiss="alert">
                  &times;
              </a>
              <?php echo Yii::$app->session->getFlash('info');?>
          </div>
      <?php endif;?>
      
  <?php foreach ($list as $k => $v): ?> 
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="data_table my_book_list">

  <thead>
    <tr>
      <td colspan="6" align="center" valign="middle" class="book_list_num">
      <!-- <span>删除</span> -->
      <p>购买时间: <?php echo date('Y-m-d H:i:s',$v['create_time']) ?>　　订单号：<strong><?php echo $v['order_no'] ?></strong></p>
      </td>
      </tr>
  </thead>
  
  <thead>
    <tr>
      <td align="center" valign="middle">商品名称</td>
      <td align="center" valign="middle">数量</td>
      <td width="300" align="center" valign="middle">商品价格</td>
      <td width="300" align="center" valign="middle">合计</td>
      <!-- <td width="300" align="center" valign="middle">合计</td> -->
      <td align="center" valign="middle">状态</td>
    </tr>
  </thead>

  <tbody>
    <?php 
    $goods_list = OrderGoods::find()->where(['order_id'=>$v['id']])->all();
    $count = count($goods_list);
     ?>

    <?php foreach ($goods_list as $key => $var): ?> 
    <tr>
      <td align="center" valign="middle"><a href="#"><?php echo $var['goods_name'] ?></a></td>
      <td align="center" valign="middle"><?php echo $var['buy_number'] ?></td>
      <td align="center" valign="middle">
        <?php echo $var['present_price']?>
        <!-- <ul class="my_book_ul"> -->
          
          <!-- <li>购物积分</li>
          <li>消费积分</li>
          <li>股权积分</li> -->
         <!--  <li class="money_cash"><?php echo $var['present_price'] ?></li>
          <li class="money_cash"><?php echo $var['account3_price'] ?></li>
          <li class="money_cash"><?php echo $var['account5_price'] ?></li> -->
   
          <!-- <div class="clear"></div> -->
          
        <!-- </ul> -->
      </td>
      <td align="center" valign="middle">
        <?php echo $var['present_price'] * $var['buy_number']?> <!-- 消费积分 -->
        <!-- <ul class="my_book_ul">
          
          <li>购物积分</li>
          <li>消费积分</li>
          <li>股权积分</li>
          <li class="money_cash"><?php echo $var['present_price']*$var['buy_number']  ?></li>
          <li class="money_cash"><?php echo $var['account3_price']*$var['buy_number'] ?></li>
          <li class="money_cash"><?php echo $var['account5_price']*$var['buy_number'] ?></li> 
          <div class="clear"></div> 
        </ul> -->
      </td>
      <!-- <td align="center" valign="middle">
        <?php echo $var['present_price']*$var['buy_number']+$var['account3_price']*$var['buy_number']+$var['account5_price']*$var['buy_number']  ?>
      </td> -->

      <?php if ($key == 0): ?>   
          <td rowspan="<?php echo $count ?>" align="center" valign="middle">
              <?php if ($v['delivery'] == 0): ?>
                  <font class="nopay">待发货</font>
              <?php endif ?>
                
              <?php if ($v['delivery'] == 1 && $v['order_status'] == 1): ?>
              
                <font class="nopay waitfor"><a href="<?= Url::to(['order/complete-order', 'id' => $v['id']])?>">待收货</a></font>
              <!-- <a href="#" class="gopay">收货</a> -->
              <?php endif ?>

            <?php if ($v['order_status'] == 4): ?>  
                <font class="nopay payfor">已完成</font>
            <?php endif ?>
          </td>
      <?php endif ?>
      
    </tr> 
    <?php endforeach ?>
    
  </tbody>    
</table>
<?php endforeach ?>
 
</div><!--购物车列表--> 
         
    <div class="pages_list">
        <?= LinkPager::widget([
          'pagination' => $pages,
          'firstPageLabel'=> '首页',
          'nextPageLabel' => '下一页',
          'prevPageLabel' => '上一页',
          'lastPageLabel' => '末页'])
        ?>   
    </div>   
  
</div>        

