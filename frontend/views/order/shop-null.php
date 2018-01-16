<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = '结算购物车';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
  <br>
  	<b><font color="#FF0000" size="4">您的购物车暂时没有商品</font>	</b>
  <div class="success_order_box">
  <p>现在您可以：<font>

    <a href="<?php echo Url::to(['product/index']) ?>">前往购物</a>
	|
  <a href="<?php echo Url::to(['site/index']) ?>">返回首页</a></font></p>
  
  </div>
</div>
