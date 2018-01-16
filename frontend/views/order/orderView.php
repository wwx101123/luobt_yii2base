<?php 
$this->title='订单详情';
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use common\models\Order;
 ?>

<div class="wraper sure_wraper">

     
     <h4 class="add_top_bg"><img src="/images/address_bg.png" alt=""/></h4>
     <div class="address_box order_address_box">

     
      <img src="/images/icon03.svg" alt="" class="address_icon"/>
      
      <h3>收货人： <?php echo $data['name'] ?></h3>
      <p>收货地址：<?php echo $data['address'] ?></p>
     
     <div class="clear"></div>
     
     </div>
     
            
       <h4 class="add_top_bg"><img src="/images/address_bg.png" alt=""/></h4>
       
      <ul class="admin_list order_time d_order_time">
      
        <li>订单时间： <?php echo date('Y-m-d H:i:s',$data['create_time']) ?></li>
        <li>订单编号：<?php echo $data['order_no'] ?></li>
        <li>配送方式：<?php echo Order::$shipping_arr[$data['shipping_id']] ?></li>
        <li>支付方式：<?php echo Order::$pay_type_arr[$data['pay_id']] ?></li>
        <li id="fh_time">订单状态： <?php echo Order::getStatus($data['id']) ?></li>
     
       </ul> 

        <?php if ($data['order_status']==1): ?>
          
        <div class="nav_select_btn order_details_btn">
             <?php if ($data['pay_status']==0): ?>
               <?php if ( $data['pay_id']==0): ?>
                 
              <a href="javascript:void(0)" id="pay_now">立即支付</a>
               <?php endif ?>
             <a href="javascript:void(0)" class="br_line">取消订单</a>
             <?php endif ?>
             
             <div class="clear"></div>
           
        </div> 
        <?php endif ?>
     
     
     <div class="index_list buy_name">
       <h4 class="buy_title"><img src="/images/icon05.svg" alt=""/>本次订单列表</h4>
       <ul class="i_ul_list sure_list de_myorder_list">
       <?php $count=0 ?>
        <?php foreach ($data['orderGoods'] as $k => $var): ?>
          <?php $count+=$var['goods_number']; ?>
         <li>
          <a href="#"><img src="<?php echo $var['goods_img'] ?>" alt="" class="list_pic"/></a>
          <h3><em class="order_vl">￥<?php echo $var['goods_price'] ?> </em><?php echo $var['goods_name'] ?></h3>
          <h5><em class="order_vl">x <?php echo $var['goods_number'] ?></em>规格： <?php echo $var['goods_attr'] ?></h5>
          <div class="clear"></div>
         </li>
        <?php endforeach ?>
         </ul>
         <ul class="qit_list">
           <li><em><?php echo $count ?> 件</em>数量合计：</li>
           <li><em>￥ <?php echo $data['shipping_fee'] ?></em>配送费：</li>
           <li><em>￥ <?php echo $data['goods_amount'] ?></em>商品总价：</li>
           <?php if ($data['use_score']): ?>
           <li><em> <?php echo $data['use_score'] ?></em>使用积分：</li>
           <li><em><strong>￥ -<?php echo $data['deduction'] ?></strong></em>积分抵扣：</li>
           <?php endif ?>
           <?php if ($data['discount']>0): ?>
            <li><em>已打 <strong><?php echo (float)$data['discount'] ?>折</strong></li>
           <?php endif ?>
           <li><em><strong>￥ <?php echo $data['order_amount'] ?></strong></em>合计(含运费)：</li>
         </ul>
     </div>

   
   </div>
<?php
$pay_url = Url::to(['wei-pay/pay']);
$orderUrl = Url::to(['order/order-view']);
$cancelUrl = Url::to(['order/cancel']);
$orderIndex = Url::to(['order/index']);
$js =<<<JS
    var id = {$data['id']};

    $('.br_line').on('tap',function(){
        layer.confirm('确定要取消该订单吗？',{
            btn:['取消订单','再想想'],
            title:false
          },function ()
          {   

               setTimeout("layer.load(2, {shade: [0.5,'#ababab']});",0);
               $.ajax({
                url:'{$cancelUrl}',
                data:{id:id},
                type:'post',
                dataType:'json',
                success:function(data){
                  layer.closeAll('loading');
                    if (data.code) {
                      layer.msg(data.msg,{icon:1},function ()
                      {
                          window.location.reload();
                      });
                    }else{
                       layer.msg(data.msg,{icon:2});
                    }
                },
                error:function(){
                    layer.closeAll('loading');
                }
               })
              /*取消订单*/
          },function ()
          {
            return;
          }

          )
    })
   $('#pay_now').on('tap',function(){
      layer.load(2,{shade: [0.3,'#fff']});
      $.ajax({
            url:'{$pay_url}',
            type: 'post',
            data:{key:{$data['id']}},
            dataType:'json',
            success:function(reslut){

                callpay(reslut);

            },
            error: function(xhr, type, errorThrown) {
            
              layer.closeAll('loading');

              layer.alert('微信支付请求超时',{icon:2},function ()
              {
                  window.location.href='{$orderUrl}?id='+id;
              });
              // $('#error').html(xhr.responseText);
            }
      });
  })

JS;
$this->registerJs($js);

?>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
  
wx.config(<?= json_encode($wechat->jsApiConfig(['jsApiList'=>['chooseWXPay']])) ?>); 
wx.ready(function(){

});
function callpay(data)
  { 
    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', jsApiCall(data), false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', jsApiCall(data)); 
            document.attachEvent('onWeixinJSBridgeReady', jsApiCall(data));
        }
    }else{

        jsApiCall(data);
    }
  }
  function jsApiCall(data) {
    layer.closeAll('loading');
     wx.chooseWXPay({
                  appId: '<?php echo $wechat->appId ?>',
                  nonceStr: data.nonceStr, // 支付签名随机串，不长于 32 位
                  package: data.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
                  signType: data.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
                  timestamp:data.timestamp,
                  paySign: data.paySign, // 支付签名
                  success: function (res) {

                      WeixinJSBridge.log(res.err_msg);

                      if (res.errMsg == 'chooseWXPay:ok'){

                        layer.msg('支付成功！',{
                            icon:1,
                            time:2000,
                        });
                            window.location.href='<?php echo Url::to(['order/index']) ?>';
                      }
                      
                     
                  },
                  error:function  (res) {

                      if (res.errMsg == 'chooseWXPay:fail'){

                        layer.msg(res,{
                            icon:2,
                            time:2000,
                        });
                            window.location.href='<?php echo Url::to(['order/index']) ?>';
                      }
                  },
                  cancel:function  (res) {
                    // alert(res);
                    if (res.errMsg == 'chooseWXPay:cancel'){

                          layer.msg('支付失败，失败原因：用户取消',{
                            icon:2,
                            time:2000,
                          });
                          window.location.href='<?php echo Url::to(['order/index']) ?>';
                      }
                      
                  }
          });
  }
</script>




