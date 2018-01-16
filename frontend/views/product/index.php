<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '购物商城';
?>

<!-- <div class="wraper"> -->

  <!-- <div class="position_on"><img src="images/icon02.png" alt=""/><em>当前位置</em>：<a href="<?= Url::to(['site/index'])?>">首页</a> > <font><?= $this->title?></font>  
  </div> -->

    <a style='font-size:15px;' href="<?= Url::to(['product/index'])?>">查看全部</a>&nbsp;&nbsp;

    <?php foreach ($sorts as $key => $sort):?>
          <a class="btn btn-primary" style='font-size:15px;' href="<?= Url::to(['product/cate', 'id' => $sort['id']])?>"><?= $sort['sort_name']?></a>&nbsp;
    <?php endforeach;?>
   
   <div class="sub_box">
   
       <ul class="rec_ul_list sub_rec_ul_list">
      <?php foreach ($product_list as $key => $v): ?>
       <li>
         <a href="<?=Url::to(['product/view','id'=>$v['id']])?>"><?= Html::img($v['goods_img'])?></a>
        <h3><a href="<?=Url::to(['product/view','id'=>$v['id']])?>"><?=$v['goods_name']?></a></h3>
        <p>售价：<strong style="color: #B90000;"><?=$v['present_price']?></strong></p>
       </li>
      <?php endforeach?>

       <div class="clear"></div>
      
   </ul>   
      
    <div class="pages_list"> 
    
    <ul class="pagination pull-left" id="yw0">
      <?= LinkPager::widget([
        'pagination' => $pages,
        'firstPageLabel'=> '首页',
        'nextPageLabel' => '下一页',
        'prevPageLabel' => '上一页',
        'lastPageLabel' => '末页'])?>   
    <div class="clear"></div>
    
    </ul>
    
    </div> <!--分页-->
   
   
   </div>
   

<!-- </div> -->