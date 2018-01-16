<?php 
use common\widgets\JsBlock;
use yii\helpers\Html;

$this->params['breadcrumbs'][] = ['label'=>'购物商城','url'=>['product/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->title=$model['goods_name'].'详情';
 ?>

<div class="show_pro_box">
               
                 <div class="big_pic"><?= Html::img($model['goods_img'])?></div>
                 <div class="pro_text">
                  
              <h3 class="pro_name"><?php echo $model['goods_name'] ?></h3>
                  <p>市 场 价：<font>￥ <?php echo $model['market_price'] ?></font></p>
                  <p>会 员 价：<em>￥<?php echo $model['present_price'] ?></em>(消费积分)</p>
                  <!-- <p>总　　额：<em>￥<?=$model['present_price']+$model['account3_price']+$model['account5_price']?></em></p> -->
                  
                  <p>商品编号： <?php echo $model['goods_code'] ?></p>
                  <p>数　　量：
                    <strong class="jian">-</strong>
                    <input type="text" id="buy_num" value="1">
                    <strong class="jia">+</strong>
                  </p>
                  <p>库　　存： <?php echo $model['inventory'] ?> 件</p>
                  <!-- <p>总　　额：<em>￥<?= $model['present_price']?></em></p> -->
                  <div class="two_btn">
                  
                    <a href="javascript:void(0)" id ="add_car">加入购物车</a>
                    <a href="javascript:void(0)" id ="add_car_btn" class="add_cart_btn">立即购买</a>
                    
                    <div class="clear"></div>    
                  
                  </div>
                   
                 </div>
                  
                  <div class="clear"></div>
                   
                   <h4 class="reg_txt">详细参数</h4>
                   
                   <div class="pro_details_box">
                   <?php echo $model['content'] ?>
                   </div> 
                  
               
               </div>
<?php JsBlock::begin(); 

$add_url = yii\helpers\Url::to(['shop-car/add-shop-car']);
$confirmUrl = yii\helpers\Url::to(['order/confirm-order']);
?>
<script type="text/javascript">
  
$("#add_car").on('click',function () {

        checkCard($(this),0);
    });
  
$("#add_car_btn").on('click',function () {

        checkCard($(this),1);
    });
function checkCard(obj,type) {
            var buy_num = $('#buy_num').val();
            if(isNaN(buy_num)){
               alert('请输入正确购买数量');
              return;
            }
            if (buy_num < 1 ) {
              alert('请添加购买数量');
              return;
            }
            var goods_id  = <?php echo $model['id'] ?>;

            addCard(goods_id,buy_num,type,obj);
      
     }
     /*type:点击的类型 加入购物车 或者是 立即购买*/
     function addCard(goods_id,buy_num,type,obj) {
            $.ajax({
                url:'<?php echo $add_url ?>',
                type:'get',
                data:{
                    goods_id:goods_id,
                    buy_num : buy_num,
                    add_type : type,
                },
                success:function (data){
                  if (data.status) {

                    if (type==1) {
                        window.location.href='<?php echo $confirmUrl ?>?id='+data.data.id;
                    } else {
                        layer.msg('加入购物车成功',{
                            icon:1,
                            time:2000,
                        });
                    }
                    
                  } else {
                    layer.msg(data.msg,{
                            icon:2,
                            time:2000,
                        });
                  }
                }
            })
     }

function checkBuyNum(){

}
     $('.jia').on('click','',function () {
          var inp = $('#buy_num')
          var value = Number(inp.val())+1;
          inp.val(value);
    });

     $('.jian').on('click','',function () {
          var inp = $('#buy_num')
          var value = Number(inp.val())-1;
          if (value>0) {
            inp.val(value);
          }
    });


</script>


<?php JsBlock::end(); ?>
