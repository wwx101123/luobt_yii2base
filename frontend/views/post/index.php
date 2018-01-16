<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title='新闻中心';
$this->params['breadcrumbs'][] = ['label'=>$this->title,];

?>                
                  

            
                      
                      
                      
                    <div class="sub_news_box">
                    
                    
                    
                         <ul class="news_box_ul">
     <?php foreach ($model as $key => $v): ?>
       
     <li>
      <h4><a href="<?=Url::toRoute(['post/details', 'id' =>$v['id']]);?>"><?=$v['title']?></a></h4>
     </li>
     <?php endforeach ?>
    </ul>                
                    
                    
                    </div> <!--新闻列表-->
   
   
    <div class="pages_list">
    
    
    
   <?php echo LinkPager::widget([
        'pagination' => $pagination,
        'firstPageLabel'=>"首页",
        'prevPageLabel'=>'上一页',
        'nextPageLabel'=>'下一页',
        'lastPageLabel'=>'尾页',
    ]);?> 
    </div> <!--分页-->
           
                  
            
