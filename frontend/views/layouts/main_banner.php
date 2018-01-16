<?php $this->beginContent('@frontend/views/layouts/main.php');?>
<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Routing;
?>


<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right accordion2" id="cbp-spmenu-s2">

      <a href="#"><?=Html::img('@web/statics/images/h_icon.png',['style'=>'width="24"'])?>网站首页</a>
      <?php foreach (Routing::getTouting() as $key => $v): ?>
  
          <?php if ($v['child']): ?>

            <h3><a href=<?= Routing::urlTo($v['url'])?>><?=$v['name']?></a></h3>

          <?php else: ?>

              <a href=<?= Routing::urlTo($v['url'])?>><?=$v['name']?></a>

          <?php endif ?> 
          <div class="sub_memu">
            <?php foreach ($v['child'] as $key => $val): ?>
               <a href=<?= Routing::urlTo($val['child_url'])?>>
                      <?=$val['child_name']?>
               </a>
            <?php endforeach ?>
          <div class="clear"></div>
          </div>

      <?php endforeach ?>
      <?= Html::a('退出',['/site/logout'],['data-method' => 'post', 'class' => 'logout'])?>

     <!--  <div class="wel_box">
    
        <div class="wraper">
         
          <p class="wel_p"></p>
          <p class="wel_login">
          <?= Html::a('我的订单', ['/order/index']);?> | 
          <?= Html::a('购物车', ['/shop-car/car']);?> | 
          <?= Html::a('注册', ['/signup/index']);?>
          <a href="#">
            <?=Html::img('@web/statics/images/icon01.png',['alt'=>""])?><?= Yii::$app->user->identity->username?></a> | <?= Html::a('退出',['/site/logout'],
                                    ['data-method' => 'post', 'class' => '']
                                    ) ?></p>
         
          <div class="clear"></div>
         
        </div>
    
      </div>  -->
</div> 

   
<div class="top">
      <div class="wraper">
     
        
        <div class="logo">
          <a href="<?= Url::to(['site/index'])?>"><?= Html::img("@web/statics/images/logo.png")?></a>
        </div>

        <div class="nav_box">
          
          <ul class="nav_ul">
  
            <?php foreach (Routing::getTouting() as $key => $v): ?>  
            <li>
            <a href="<?= Routing::urlTo($v['url'])?>"><?=$v['name']?></a>
            <div class="show_nav">
              <?php foreach ($v['child'] as $key => $val): ?>
                <a href="<?=Routing::urlTo($val['child_url'])?>">
                  <?=$val['child_name']?>
                </a>
              <?php endforeach ?>
             </div>    
            </li>
            <?php endforeach ?>
            <li> 
            <!-- <a id = 'logout' onclick="logout()" href="<?= Url::to(['site/logout'])?>">安全退出</a> -->
           <!--  <a data-method = "post" class="loout"> href="<?= Url::to(['site/logout'])?>">安全退出</a> -->

           <?php echo Html::a('安全退出', ['/site/logout'], ['data-method' => 'post', 'data-confirm' => '确定要安全退出系统吗?', 'class' => 'logout'])?>
           <?php //= Html::a('安全退出',['/site/logout'],['data-method' => 'post', 'data-confirm' => '确定安全退出系统吗2222？','class' => ''])?>
            </li>
            <div class="clear"></div>

          </ul>
        
         <?= Html::img('@web/statics/images/menu.png',['alt'=>"",'class'=>"menu",'id'=>"showRightPush"])?>
        
        </div>
        
        <div class="clear"></div>
     
     </div>
   
</div>

        <?=$content ?>

<?php $this->endContent();?>

<script type="text/javascript">

$(document).ready(function(){
    
  $(".information_box .in_title").bind("click",function(){
     
   $(".information_box .information_list").slideToggle();
  
  })

})

$(document).ready(function(){
  
  $(".show_nav").hide();
   
   $(".nav_ul li").hover(function(){
     
        
      $(this).children(".show_nav").show();
     
     
     },function(){
        
        $(this).children(".show_nav").hide();
       
       });    

});

$(document).ready(function(){
  
/*  $(".accordion2 h3").eq(0).addClass("active");
  $(".accordion2 p").eq(0).show();*/
    
  $(".accordion2 .sub_memu").hide();

  $(".accordion2 h3").click(function(){
    $(this).next("div").slideToggle("slow")
    .siblings("div:visible").slideUp("slow");
    $(this).toggleClass("active");
    $(this).siblings("h3").removeClass("active");
  });

});
      var menuRight = document.getElementById( 'cbp-spmenu-s2' ),
                showRightPush = document.getElementById( 'showRightPush' ),

        body = document.body;
      
          showRightPush.onclick = function() {
        classie.toggle( this, 'active' );
        classie.toggle( body, 'cbp-spmenu-push-toleft' );
        classie.toggle( menuRight, 'cbp-spmenu-open' );
        disableOther( 'showRightPush' );
      };

      function disableOther( button ) {

        if( button !== 'showRightPush' ) {
          classie.toggle( showRightPush, 'disabled' );
        }
      }
      

</script>

