<?php $this->beginContent('@frontend/views/layouts/main.php');?>
<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Routing;
?>
   <div class="wel_box">
    
      <div class="wraper">
       
       <p class="wel_p"></p>
       <p class="wel_login">
       <?= Html::a('我的订单', ['/order/index']);?> | 
       <?= Html::a('购物车', ['/shop-car/car']);?> | 
       <?= Html::a('注册', ['/signup/index']);?>
       <a href="#">
        <?=Html::img('@web/statics/images/icon01.png',['alt'=>""])?><?= Html::a('登录',['/site/login'],
                                ['data-method' => 'post', 'class' => '']
                                ) ?>
                                </p>
       
       <div class="clear"></div>
       
      </div>
    
    </div>
   
   <div class="top">
     

     
     <div class="wraper">
     
        
        <div class="logo"><a href="<?=Url::to(['site/index'])?>"><?= Html::img("@web/statics/images/logo.png")?></a></div>
        
        <div class="clear"></div>
     
     </div>
     
   
   </div>

    <div class="banner">
     <?= Html::img("@web/statics/images/banner.jpg", ['class'=>'pc_banner']);?>
     <?= Html::img("@web/statics/images/m_banner.jpg", ['class'=>'m_banner']);?>
     </div>
    <div class="panel panel-default">
        <div class="panel-heading"></div>
        <div class="panel-body">123123
              <?=$content ?>
        </div>
    </div>
         <div class="wraper foot_link white-bg">
         		<h1>合作伙伴</h1><hr>
         			<div class="row">
                    		<div class="col-md-2 col-xs-4"><?= Html::img("@web/statics/images/icon/1.jpg")?></div>
                            <div class="col-md-2 col-xs-4"><?= Html::img("@web/statics/images/icon/2.jpg")?></div>
                            <div class="col-md-2 col-xs-4"><?= Html::img("@web/statics/images/icon/3.jpg")?></div>
                            <div class="col-md-2 col-xs-4"><?= Html::img("@web/statics/images/icon/4.jpg")?></div>
                            <div class="col-md-2 col-xs-4"><?= Html::img("@web/statics/images/icon/5.jpg")?></div>
                            <div class="col-md-2 col-xs-4"><?= Html::img("@web/statics/images/icon/6.jpg")?></div>
                            <div class="col-md-2 col-xs-4"><?= Html::img("@web/statics/images/icon/7.jpg")?></div>
                            <div class="col-md-2 col-xs-4"><?= Html::img("@web/statics/images/icon/8.jpg")?></div>
                    
                    </div>
         </div>
         <div class="footer">
                <div class="center-block">
                   <ul class="list-inline">
  <li><a href="#">关于我们</a></li>
  <li><a href="#">联系我吗</a></li>
  <li><a href="#">法律声明</a></li>
  <li><a href="#">隐私政策</a></li>
  <li><a href="#">许可协议</a></li>
  <li><a href="#">友情链接</a></li>
</ul>
                </div>
                <div class="m-t">
                    <strong>Copyright</strong> <?= Yii::$app->name?>
                </div>
                 <div class="m-t">
                 <?= Html::img("@web/statics/images/icon/9.jpg")?>
                 <?= Html::img("@web/statics/images/icon/10.jpg")?>
                 </div>
            </div>
         
         
      </div>
  </div>
</div>

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

