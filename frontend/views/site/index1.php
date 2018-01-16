index1.php<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Parameter;
use common\models\Product;
?>
<div class="wraper wraper_info">

 <?php if(isset($member)):?> 
    <div class="information_box">
                 
       
       <div class="info_border">
       
            <h3 class="in_title">会员信息INFO</h3>
                 
                 <div class="information_list">
                 
                    <ul class="information_ul">
                      
                      <li><img src="<?= Url::to("@web/statics/images/i01.png")?>" alt=""/><em class="col01">消费商号：</em><?= $member->username?></li>
                      <!-- <li><img src="<?= Url::to('@web/statics/images/i02.png')?>" alt=""/><em class="col02">服务中心：</em>服务中心</li> -->
                      <li><img src="<?= Url::to('@web/statics/images/i03.png')?>" alt=""/><em class="col03">代理等级：</em><?= Parameter::getGlevelName($member->g_level)?></li>
                      <li><img src="<?= Url::to('@web/statics/images/i04.png')?>" alt=""/><em class="col04">累计佣金：</em><?= $member->account->account2?></li>
                      <li><img src="<?= Url::to('@web/statics/images/i06.png')?>" alt=""/><em class="col06">消费积分：</em><?= $member->account->account3?></li>
                      <li><img src="<?= Url::to('@web/statics/images/i06.png')?>" alt=""/><em class="col02">注册积分：</em><?= $member->account->account4?></li>
                      <li><img src="<?= Url::to('@web/statics/images/i05.png')?>" alt=""/><em class="col05">购物积分：</em><?= $member->account->account7?></li>
                      
                    
                    </ul>   
                 
                 
                 </div> 
       
       </div>
              
              
  </div><!--会员信息-->
<?php endif;?>

</div>
 

<div class="tjsp_bg">
  
  
  <div class="wraper">
  
     <h3 class="tj_tit">推荐商品</h3>
     
     <ul class="cp_list">
      <?php foreach ($rand_model as $key => $val): ?>
        
       <li>
        <a href="<?=Url::to(['product/view','id'=>$val['id']])?>"><?= Html::img($val['goods_img'])?></a>
        <h4><a href="<?=Url::to(['product/view','id'=>$val['id']])?>"><?=$val['goods_name']?></a></h4>
        <p>售价：<strong><?=$val['present_price']?></strong></p>
       </li>
      <?php endforeach ?>
       
       <div class="clear"></div>
     
     </ul> 
  
  </div>


</div>
 

<div class="wraper">
   
  <?php foreach ($sort as $key => $v): ?>
      
    <div class="f_box">
      <?php if (Product::getBigGoods($v['id'])): ?>
      <h3 class="tj_tit f_tit01"><strong><?=$v['sort_name']?></strong></h3>
        <ul class="cp_list">

          <?php foreach (Product::getBigGoods($v['id']) as $key => $value): ?>
             <li>
                <a href="<?=Url::to(['product/view','id'=>$value['id']])?>"><?= Html::img($value['goods_img'])?></a>
                <h4><a href="<?=Url::to(['product/view','id'=>$value['id']])?>"><?=$value['goods_name']?></a></h4>
                <p>售价：<strong><?=$value['present_price']?></strong></p>
             </li>
          <?php endforeach ?>
         
       <div class="clear"></div>
     
     </ul>
      <?php endif ?>
     
    
    </div><!--1F-->
    
  <?php endforeach ?>
    
   
 
 </div>
   
   
<!--    <div class="news_box">
 
   
   <div class="wraper">
   
   
    <h1 class="rec_h1">新闻中心</h1>
   
   <ul class="news_box_ul">
     
     <li>
      <h4><a href="#">公告牌显示了每个主管人员的工作进展情况</a></h4>
      <p><a href="#">近年来，直销产业跨界与融合现象越来越多，市场需求的变化也不断加快，
这对于很多行业、很多企业来说，新的挑战。对于直销企业管理
者来说，则需要具备更的意识。</a></p>
     </li>
     
          <li>
      <h4><a href="#">公告牌显示了每个主管人员的工作进展情况</a></h4>
      <p><a href="#">近年来，直销产业跨界与融合现象越来越多，市场需求的变化也不断加快，
这对于很多行业、很多企业来说，都构成了销企业管理
者来说，则需要具备更加的意识。</a></p>
     </li>
     
     <div class="clear"></div>
   
   </ul>
     
   
   </div>
    -->