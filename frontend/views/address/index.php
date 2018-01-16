<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理我的收货地址';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wraper last_add_wraper">

     
     <h4 class="add_top_bg"><img src="/images/address_bg.png" alt=""/></h4>
     <div class="address_box">


       <?php echo Html::a('<h6>新增收货地址</h6>',['address/create','id'=>$id]) ?>
     
     <div class="clear"></div>
     
     </div>
     
            
       <h4 class="add_top_bg"><img src="/images/address_bg.png" alt=""/></h4>

        
    <?php foreach ($list as $k => $var): ?>
        
    <div data-id=<?php echo $var['id'] ?> class="address_box de_adress_list <?php if ($var['status']==1): ?>
        selletctd_add
    <?php endif ?>">

     
      <img src="/images/icon03.svg" alt="" class="address_icon"/>
      
      <h3>收货人： <?php echo $var['name'] ?></h3>
      <p>收货地址：<?php echo $var['address'] ?></p>
      <p>联系电话：<?php echo $var['tel'] ?></p>
     
     <div class="clear"></div>
     
     </div>
    <?php endforeach ?>

   </div>
<?php  
$url = Url::to(['choose-address']);
$ret_url = Url::to(['order/confirm-order','id'=>$id]);
$js = <<<JS
    $('.de_adress_list').on('click',function ()
    {   
        var id = $(this).attr('data-id');
        $.ajax({
            url:'{$url}',
            data:{id:id},
            type:'get',
            success:function (data){
                  if (data.code) {
                    window.location.href='{$ret_url}';

                  }else{
                    layer.alert(data.msg);
                  }
              }
        });
    })
JS;

$this->registerJs($js);



?>