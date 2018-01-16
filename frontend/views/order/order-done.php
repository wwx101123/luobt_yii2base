 <?php 
$this->title = '我的订单';
use yii\helpers\Url;
use frontend\assets\AppAsset;

  ?>

 <div class="cart_box">
                 
                 <div class="row-top"><?=yii\helpers\Html::img('@web/statics/images/shopcar-icon03.png')?> 核对订单　<?=yii\helpers\Html::img('@web/statics/images/step03.png')?></div>
                 
                 
                 <div class="success_order_box">

  <h4>订单已成功提交  <em>我们会尽快安排配送</em></h4>
  <p>订单提交成功，现在您还可以：<font><a href="<?php echo Url::to(['order/index']) ?>">查看购买记录</a><a href="<?php echo Url::to(['product/index']) ?>">继续购物</a></font></p>
  
  </div>
               
               
               </div>



