<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = '首页';
?>
<div class="wraper">
   
   <div class=" white-bg dashboard-header panel icon_6  clearfix ">
        
        <div class="col-md-2 col-xs-4"><a href="<?= Url::toRoute('member/update')?>">
         <div class="center-block"> <img src="<?= Url::to('@web/statics/Images/icon1.jpg')?>" class="center-block img-responsive">
                <h3 class=" text-info text-center"> 修改资料 </h3>
              </div>
       </a> </div>
         <div class="col-md-2 col-xs-4"><a href="<?= Url::toRoute('site/update-password')?>">
         <div class="center-block"> <img src="<?= Url::to('@web/statics/Images/icon2.jpg')?>" class="center-block img-responsive">
                <h3 class=" text-info text-center"> 修改密码 </h3>
              </div>
       </a> </div>
         <div class="col-md-2 col-xs-4"><a href="<?= Url::toRoute('signup/index')?>">
         <div class="center-block"> <img src="<?= Url::to('@web/statics/Images/icon3.jpg')?>" class="center-block img-responsive">
                <h3 class=" text-info text-center"> 会员注册 </h3>
              </div>
       </a> </div>
         <div class="col-md-2 col-xs-4"><a href="<?= Url::toRoute('bonus/index')?>">
         <div class="center-block"> <img src="<?= Url::to('@web/statics/Images/icon4.jpg')?>" class="center-block img-responsive">
                <h3 class=" text-info text-center"> 奖金明细 </h3>
              </div>
       </a> </div>
         <div class="col-md-2 col-xs-4"><a href="<?= Url::toRoute('report/index')?>">
         <div class="center-block"> <img src="<?= Url::to('@web/statics/Images/icon5.jpg')?>" class="center-block img-responsive">
                <h3 class=" text-info text-center"> 反馈中心 </h3>
              </div>
       </a> </div>
         <div class="col-md-2 col-xs-4"><a href="<?= Url::toRoute('tree/tree-ajax')?>">
         <div class="center-block"> <img src="<?= Url::to('@web/statics/Images/icon6.jpg')?>" class="center-block img-responsive">
                <h3 class=" text-info text-center"> 推荐关系 </h3>
              </div>
       </a> </div>
      
     
     </div>
   
   
   
   
   
   
   
   <div class="wraper_info">
     
     
         <div class="information_box">

            <div class="in_title">
              
              <!--<img src="images/user.png" alt=""/>-->
              <h5>个人信息</h5>
              <h2>Personal information</h2>
              <h6><img src="<?= Url::to('@web/statics/images/down.png')?>" alt="" class="down_icon"/></h6>
         </div>
                 
                 <div class="information_list">
                 
                    <ul class="information_ul">

                      <li><span><img src="<?= Url::to('@web/statics/images/i02.png')?>" alt=""/><em><?= $member->getAttributeLabel('is_agent')?></em></span><?= $is_agent?></li>

                      <li><span><img src="<?= Url::to('@web/statics/images/i03.png')?>" alt=""/><em><?= $member->getAttributeLabel('g_level')?></em></span><?= $g_level?></li>

                      <li><span><img src="<?= Url::to('@web/statics/images/i04.png')?>" alt=""/><em><?= $member->account->getAttributeLabel('account2')?></em></span><?= Html::encode($member->account->account2);?></li>

                      <li><span><img src="<?= Url::to('@web/statics/images/i05.png')?>" alt=""/><em><?= $member->account->getAttributeLabel('account5')?></em></span><?= Html::encode($member->account->account5)?></li>

                      <li><span><img src="<?= Url::to('@web/statics/images/i06.png')?>" alt=""/><em><?= $member->account->getAttributeLabel('account3')?></em></span><?= Html::encode($member->account->account3)?></li>

                       <li><span><img src="<?= Url::to('@web/statics/images/i04.png')?>" alt=""/><em><?= $member->account->getAttributeLabel('account4')?></em></span><?= Html::encode($member->account->account4)?></li>

                       
                      
                      <div class="clear"></div> 
                      
                    </ul>     
                 
                 </div>           
              
  </div>
   
   
   </div><!--会员信息-->
  
  
  <div class="news_box">
 
   <div class="wraper"> 
   
    <h1 class="rec_h1"><a href="#" class="news_more">MORE</a>新闻公告</h1>
   
   <ul class="news_box_ul">
     <?php foreach($news as $simple_news):?>
     <li>
      <?php if ($simple_news['is_top']): ?>

        <h4>
          <?= Html::img("@web/statics/images/hot.gif")?><a href="<?= Url::to(['post/details', 'id' => $simple_news['id']])?>"><?= $simple_news['title']?></a>
        </h4>
        <p>
          &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?= Url::to(['post/details', 'id' => $simple_news['id']])?>"><?= mb_substr(str_replace('&nbsp;','',strip_tags($simple_news['content'])),0,60,'utf-8')?>
          </a>
        </p>
      <?php else: ?>
        <h4>
          <a href="<?= Url::to(['post/details', 'id' => $simple_news['id']])?>"><?= $simple_news['title']?></a>
        </h4>
      <p>
        &nbsp;&nbsp;&nbsp;&nbsp;<a href="<?= Url::to(['post/details', 'id' => $simple_news['id']])?>"><?= mb_substr(str_replace('&nbsp;','',strip_tags($simple_news['content'])),0,60,'utf-8')?>
        </a>
      </p>
      <?php endif ?>
     
     </li>
   <?php endforeach;?>
     
     <div class="clear"></div>
   
   </ul>
     
   
   </div>
   
 
 </div><!--news-->

  
 <div class="clear"></div> 

</div>
