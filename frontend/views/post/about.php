<?php 
	use common\models\Post;
	use yii\helpers\Url;
  $this->title = '关于我们';
  $this->params['breadcrumbs'][] = ['label'=>$this->title,];
?>
            
                      
                      
                      
              <div class="newstxt">
       	
       <h1 class="news_tit_h1"><?=$model['title']?></h1>
       <h3 class="news_time">发布时间：<?=date('Y/m/d H:i:s', $model['created_at'])?></h3>
             <img src="<?=$model['label_img']?>" alt="" style=""/>

       <p><?=$model['content']?></p> 


     <div class="next_pre">
    
   </div><!--新闻详情-->
   
   

           
                  
                  </div>
            


        
        
   

