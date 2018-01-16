<?php 
	use common\models\Post;
	use yii\helpers\Url;
  $this->title = $model['title'];

$this->params['breadcrumbs'][] = ['label' => '新闻中心', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
            
                  <!--<div class="container">-->
                      
                      
                      
              <div class="newstxt">
       	
       <h1 class="news_tit_h1"><?=$model['title']?></h1>
       <h3 class="news_time">发布时间：<?=date('Y/m/d H:i:s', $model['create_at'])?></h3>
             <img src="<?=$model['label_img']?>" alt="" style=""/>

       <div class="postbody">
       <?=$model['content']?>
		</div>

     <div class="next_pre  text-right">
     <?php if ($prev){ ?>
     	
     <font>上一篇：<a href="<?=Url::toRoute(['post/details', 'id' =>$prev['id']]);?>"><?= $prev['title']?></a></font>
     <?php } else{?>
     	<font>上一篇：<a href=""><?= '没有了'?></a></font>
     	<?php }?>


 	<?php if ($Next){ ?>
     	
     <font>下一篇：<a href="<?=Url::toRoute(['post/details', 'id' =>$Next['id']]);?>"><?= $Next['title']?></a></font>
     <?php } else{?>
     	<font>下一篇：<a href=""><?= '没有了'?></a></font>
     	<?php }?>

   
   </div><!--新闻详情-->
   
   

           
                  
                  </div>
            


        
        
 <!--   </div>-->
    
    
    </div>
    

