<?php 
$this->title='确认订单';
 $this->params['breadcrumbs'][] = $this->title;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\widgets\JsBlock;
use common\models\Member;

 ?>

<?php $form = ActiveForm::begin([
      'action'=>['order/add-order'],
     ]);?>
                 
  <div class="row-top"><?=Html::img('@web/statics/images/shopcar-icon02.png')?> 核对订单　<?=Html::img('@web/statics/images/step02.png')?>
  </div>
   
  <h4 class="reg_txt sure_address">确认收货地址</h4>
   
  <div class="address_list">   

    <?php foreach ($address as $k => $v): ?>     
      <div class="sh_address sh_address_on">
        <p>
        <input <?php echo $v['status'] == 1 ? 'checked' : ''?> name="address_id" type="radio" value="<?php echo $v['id'] ?>" >
        地址： <?php echo $v['address'] ?></p>
        <p class="shouren">收货人： <?php echo $v['name'] ?>  &nbsp;&nbsp;联系电话： <?php echo $v['tel'] ?></p>
        <a href="javascript:void()" data-key=<?php echo $v['id'] ?> data-toggle = 'modal' data-target = '#page-modal' class="xiugai_link j-edit ">（修改）</a> 

        <?php echo $v['status']==1?'':'<a   class="xiugai_link_a" data-key='.$v["id"].'> （设为默认地址）</a> ' ?> 
      </div>
    <?php endforeach ?>
    
    <a data-toggle = 'modal' data-target = '#page-modal' class="tjshdz_link j-add">增加收货地址</a> 
  </div>    
   
  <h4 class="reg_txt sure_address">
    <em class="creditcard_red">
    </em>
    产品信息
  </h4>
                 
<div class="data_box">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="data_table">
    <thead>
      <tr>
        <td align="center" valign="middle" width="100">图片</td>
        <td align="center" valign="middle">商品名称</td>
        <td align="center" valign="middle" width="300">单价</td>
        <td align="center" valign="middle">购买数量</td>
        <td align="center" valign="middle" width="300">总价</td>
      </tr>
    </thead>


    <tbody>
    <?php foreach ($list as $key => $v): ?>
      <tr>
        <input type="hidden" name="id[]" value="<?=$v['id']?>">
        <td align="center" valign="middle"><?= Html::img($v['goods_img'])?></td>
        <td align="center" valign="middle"><?php echo $v['goods_name'] ?></td>
        <td align="center" valign="middle">
            <?php echo $v['present_price']?>
            <!-- <ul class="my_book_ul">
            <li>购物积分</li>
            <li>消费积分</li>
            <li>股权积分</li>
            <li class="money_cash"><?php echo $v['present_price'] ?></li>
            <li class="money_cash"><?php echo $v['account3_price'] ?></li>
            <li class="money_cash"><?php echo $v['account5_price'] ?></li>
            <div class="clear"></div>   
            </ul> -->
        </td>
        <td align="center" valign="middle"><?php echo $v['goods_num'] ?></td>
        <td align="center" valign="middle">
            <?php echo $v['present_price']?>   
            <!-- <ul class="my_book_ul"> 
            <li>购物积分</li>
            <li>消费积分</li>
            <li>股权积分</li>
            <li class="money_cash"><?php echo $v['present_price'] * $v['goods_num'] ?></li>
            <li class="money_cash"><?php echo $v['account3_price'] * $v['goods_num'] ?></li>
            <li class="money_cash"><?php echo $v['account5_price'] * $v['goods_num'] ?></li> 
            <div class="clear"></div>  
            </ul> -->
        </td>
      </tr>
    <?php endforeach ?>      
    </tbody>  
  </table>

</div><!--购物车列表-->
  <!-- <div class="clear_order_cart"><a href="<?php echo Url::to(['shop-car/car']) ?>" class="back_link"><button type="button">返回购物车</button></a></div> --> 

        <!-- <div class="col-md-6">
            <div class="col-md-4">
                <div class="col-md-3"></div>
                <div class="col-md-9">
                  您的消费积分:<?php echo $account->account5 ?>
                </div>

            </div> -->
           

             <div class="buy_now_box"> 
              <br/>
              <p style="display:inline-block; margin-right: 55px;">您的消费积分: <span style="font-size: 17px;color:red;"><?php echo $account->account5 ?></span></p>   
                    <p style="display:inline-block;">商品总数：<strong><?php echo $count ?></strong></p>
                    <p>总金额：<em><?php echo $amount ?></em> 元</p>
                    <!-- <p class="sure_password"><input type="password" name="passopen"><font>*请输入二级密码</font></p> -->  
                   
                    <a href="<?php echo Url::to(['shop-car/car']) ?>" class="back_link"><button type="button">返回购物车</button></a>
                    <button>确认购买</button> 
              </div>   
           
          
            
            
                
            

               
               
<!-- </div> -->
<?php ActiveForm::end(); ?>

<?php 
JsBlock::begin();
$orderIndex = Url::to(['order/order-done']);
$createUrl = yii\helpers\Url::to(['address/create']);
$updateUrl = yii\helpers\Url::to(['address/update']);
$defualtUrl = yii\helpers\Url::to(['address/set-defualt']);
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
        layer.load(2,{shade: [0.3,'#fff']});
        $.ajax({
              url: form.attr('action'),
              type: 'post',
              data: form.serialize(),
              success: function (data) {
                  lock = false;
                  layer.closeAll('loading');
                   $('#submitBtn').removeAttr("disabled");
                    if (data.status) {
                         layer.alert(data.msg,{icon:1},function ()
                         {
                            window.location.href='<?= $orderIndex ?>?id='+data.data.id;
                         })
                    }else{
                        layer.msg(data.msg,{icon:2})
                    }
              }
      });
    })
    
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



