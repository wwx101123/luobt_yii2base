<?php 
$this->title='确认订单';
 $this->params['breadcrumbs'][] = $this->title;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\widgets\JsBlock
use common\models\Member;

 ?>

<?php $form = ActiveForm::begin([
      'action'=>['order-new/pay-order'],

     ]);?>
<div class="cart_box">
                 
                 <div class="row-top"><img src="/images/shopcar-icon02.png" alt=""> 核对订单　<img src="/images/step02.png" alt=""></div>
 <h4 class="reg_txt sure_address">确认收货地址</h4>
                 
                 
                 <div class="address_list">
                   
                   	<?php foreach ($list as $key => $v): ?>
                   	  	
                    <div class="sh_address sh_address_on">
                      <p>
       				<input <?php echo $v['is_default']==1?'checked':'' ?>    name="address_id" type="radio" value="<?php echo $v['id'] ?>" >
                      地址： <?php echo $v['con_address'] ?></p>
                      <p class="shouren">收货人： <?php echo $v['con_name'] ?> ，联系电话： <?php echo $v['con_tel'] ?></p>
                      <a href="javascript:void()" data-key=<?php echo $v['id'] ?> data-toggle = 'modal' data-target = '#page-modal' class="xiugai_link j-edit">（修改）</a> 

                      <a href="javascript:void()" data-key=<?php echo $v['id'] ?> style="left:800px;" class="xiugai_link j-del_address">（删除）</a>

                    </div>
                 <?php endforeach ?>  
               
                    <a data-toggle = 'modal' data-target = '#page-modal' class="tjshdz_link j-add">增加收货地址</a>
                 
                 </div>
                 <h4 class="reg_txt sure_address">
                  产品信息
                  </h4>
                 
                 
                 
                 <div class="data_box">
      
   
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="data_table">
   	
  <thead>
    <tr>
      <td align="center" valign="middle">配图</td>
      <td align="center" valign="middle">商品名称</td>
      <td align="center" valign="middle">价格</td>
      <td align="center" valign="middle">商品数量</td>
      <td align="center" valign="middle">总金额</td>
    </tr>
  </thead>


  <tbody>
    <tr>
      <td align="center" valign="middle"><img src="<?=$model['goods_img'] ?>" width='50' height='50' alt=""></td>
      <td align="center" valign="middle"><?php echo $model['goods_name'] ?></td>
      <td align="center" valign="middle"><?php echo $model['present_price'] ?></td>
      <td align="center" valign="middle"><?php echo $buy_num ?></td>
      <td align="center" valign="middle"><?php echo $model['present_price']*$buy_num ?></td>
    </tr>
      
    
  </tbody>
  
  
</table>
  
 

   </div><!--购物车列表-->
   <input type="hidden" name="buy_num" value= "<?= $buy_num ?>" > 
   <input type="hidden" name="goods_id" value= "<?= $model['id'] ?>" > 
   <div class="clear_order_cart"><a href="<?php echo Url::to(['goods/index']) ?>" class="back_link"><button type="button">返回购物车</button></a></div>
 
     <div class="buy_now_box">
      
      <p>商品总数：<strong><?php echo $buy_num ?></strong></p>
      <p>商品总金额：<em><?php echo $model['present_price']*$buy_num ?></em> 元</p>
      <p class="sure_password"><input type="password" name="passopen"><font>*请输入二级密码</font></p>
      
      <button>确认购买</button>
      
    </div>              
               
               
</div>
 <?php ActiveForm::end(); ?>

<?php 
JsBlock::begin();
$orderIndex = Url::to(['order-new/index']);
$createUrl = yii\helpers\Url::to(['address-new/create']);
$updateUrl = yii\helpers\Url::to(['address-new/update']);
$defualtUrl = yii\helpers\Url::to(['address-new/set-defualt']);
 ?>
<script type="text/javascript">
  
    var lock = false;
    $('#w0').on('beforeSubmit', function(e) {
    $('#submitBtn').attr({"disabled":"disabled"});

    var pay_id = $('#w0 input[name="pay_id"]:checked').val();
    

    if (lock) {
      return;
    }
    
    var form = $(this);
    layer.confirm('确定购买吗？',{
      btn:['是的','不']
    },function () {
        lock = true;
        var goods_id = <?=$model['id']?>;
        var buy_num = <?=$buy_num?>;
        layer.load(2,{shade: [0.3,'#fff']});
        $.ajax({
              url: form.attr('action'),
              type: 'post',
              data: form.serialize(),
              success: function (data) {
                  lock = false;
                  layer.closeAll('loading');
                   $('#submitBtn').removeAttr("disabled");
                  // if (pay_id==1 ||$type==1) {
                    if (data.code) {
                         layer.alert(data.msg,{icon:1},function ()
                         {
                            window.location.href='<?= $orderIndex ?>?id='+data.data.id;
                         })
                    }else{
                        layer.msg(data.msg,{icon:2})
                    }
                  // }

              }
      });
    });
    
  }).on('submit', function(e){
      e.preventDefault();
  });

   fn('.j-add','<?php echo $createUrl ?>','','新建'); 
   fn('.xiugai_link_a','<?php echo $defualtUrl ?>','','新建'); 
   fn('.j-edit','<?php echo $updateUrl ?>', '', '编辑');
</script>

<?php 
JsBlock::end();

 ?>



