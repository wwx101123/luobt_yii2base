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


       <?php echo Html::a('<h6>新增收货地址</h6>',['address/create']) ?>
     
     <div class="clear"></div>
     
     </div>
     
            
       <h4 class="add_top_bg"><img src="/images/address_bg.png" alt=""/></h4>

        
    <?php foreach ($list as $k => $var): ?>
    <a href="<?php echo Url::to(['update','id'=>$var['id']]) ?>"> 
    <div data-id=<?php echo $var['id'] ?> class="address_box de_adress_list ">

     
      <img src="/images/icon03.svg" alt="" class="address_icon"/>
      
      <h3><?php echo $var['address'] ?></h3>
      <p> <?php echo $var['name'] ?>    联系电话：<?php echo $var['tel'] ?></p>

      <p></p>
     
     <div class="clear"></div>
     
     </div>
     </a>
    <?php endforeach ?>

   </div>
<?php  
// $url = Url::to(['choose-address']);
// $ret_url = Url::to(['order/confirm-order','id'=>$id]);
// $js = <<<JS
//     $('.de_adress_list').on('click',function ()
//     {   
//         var id = $(this).attr('data-id');
//         $.ajax({
//             url:'{$url}',
//             data:{id:id},
//             type:'get',
//             success:function (data){
//                   if (data.code) {
//                     window.location.href='{$ret_url}';

//                   }else{
//                     layer.alert(data.msg);
//                   }
//               }
//         });
//     })
// JS;

// $this->registerJs($js);



?>