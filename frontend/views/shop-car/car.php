<?php 
use  yii\helpers\Url;
use common\widgets\JsBlock;
use common\models\Member;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = '购物车';
$this->params['breadcrumbs'][] = ['label'=>'购物商城','url'=>['product/index']];
$this->params['breadcrumbs'][] = $this->title;

// $cssString = "/statics/css/magic-check.css";  
// $this->registerCssFile($cssString); 
 ?>


<div class="cart_box">

                 
                 <div class="row-top"><?= Html::img("@web/statics/images/shopcar-icon.png");?> 我的购物车　<?= Html::img("@web/statics/images/step01.png");?></div>
                 
                 <div class="data_box">
      
   
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="data_table">
   
  <thead>
    <tr>
      <td align="center" valign="middle" width="100">商品图片</td>
      <td align="center" valign="middle">商品名称</td>
      <td align="center" valign="middle" width="300">商品价格</td>

      <td align="center" valign="middle">商品数量</td>
      <td align="center" valign="middle" width="300">总价</td>
      <td align="center" valign="middle">操作</td>
    </tr>
  </thead>

  <tbody>
  <?php foreach ($list as $k => $v): ?>
    
    <tr data-id = <?php echo $v['id'] ?>>
      <td align="center" valign="middle"><?= Html::img($v['goods_img'])?></td>
      <td align="center" valign="middle"><?php echo $v['goods_name'] ?></td>
      <td align="center" valign="middle">
          <?php echo $v['present_price'];?>
          <!-- <ul class="my_book_ul">  -->
          <!-- <li>购物积分</li> -->
          <!-- <li>消费积分</li> -->
          <!-- <li>股权积分</li> -->
          <!-- <li class="money_cash"><?php echo $v['present_price'] ?></li> -->
          <!-- <li class="money_cash"><?php echo $v['account3_price'] ?></li> -->
          <!-- <li class="money_cash"><?php echo $v['account5_price'] ?></li> -->
          <!-- <div class="clear"></div>    -->
          <!-- </ul> -->

      </td>
     
      <td align="center" valign="middle"><?php echo $v['goods_num'] ?></td>
      <td align="center" valign="middle">
        <!-- <ul class="my_book_ul">
          <li>购物积分</li>
          <li>消费积分</li>
          <li>股权积分</li>
          <li class="money_cash"><?php echo $v['present_price'] * $v['goods_num'] ?></li>
          <li class="money_cash"><?php echo $v['account3_price'] * $v['goods_num'] ?></li>
          <li class="money_cash"><?php echo $v['account5_price'] * $v['goods_num'] ?></li>      
          <div class="clear"></div>
        </ul> -->
        <?php echo $v['present_price'] * $v['goods_num']?>
      </td>
     
      <td align="center" valign="middle"><a href="javascript:void(0)" class="pro_link_a">删除</a></td>
    </tr> 
     <?php endforeach ?>  
  </tbody>   
</table> 

</div><!--购物车列表-->          
  <br/>
  <div class="clear_order_cart">
      <button id="del_car" type="button">清空购物车</button>
      <a href="<?php echo Url::to(['product/index']); ?>" class="back_link"><button>返回继续购物</button></a></div>
 
    <div class="buy_now_box">
      
      <p>商品总数：<strong><?php echo $count ?></strong></p>
      <p></p>
      <p>总金额：<em><?php echo $amount ?></em> 元</p>

      <button id="buy" onclick="window.location.href='<?php echo Url::to(['order/confirm-order']) ?>'">
          <?php echo Yii::t('app', '立即下单')?>
      </button>
      
    </div>                      
  </div>
<?php 
JsBlock::begin();
$delCarUrl = Url::to(['shop-car/del-shop-car']);
$delCarOneUrl = Url::to(['shop-car/delete']);
 ?>

<script type="text/javascript">

/*清空购物车*/
  $('#del_car').on('click',function(){
    layer.confirm('删除购物车所有商品', {
      btn: ['是的','算了'] //按钮
    }, function(){
    $.ajax({
        url:'<?php echo $delCarUrl ?>',
        type:'post',
        dataType:'json',
        success:function(data){
            if (data.status) {
                
              layer.msg(data.msg,{icon:1},function ()
              {
                  window.location.reload();
              });
            }else{
              layer.msg(data.msg,{icon:2});
            }
        },
      });
    });
  });

  $('.pro_link_a').on('click',function () {
      var obj =$(this).closest('tr');
      var id = obj.attr('data-id');
      $.ajax({
        url:'<?php echo $delCarOneUrl ?>',
        type:'post',
        dataType:'json',
        data:{'id':id},
        success:function(re){
            if (re.status) {
              layer.msg(re.msg,{icon:1},function(){
                window.location.reload();//刷新当前页面.
              });
            }else{
              layer.msg(re.msg,{icon:2});
            }
        }
      })
  })
  $('.buy').on('click',function () {

  })

</script>


<?php JsBlock::end() ?>



