<?php $this->beginContent('@frontend/views/layouts/main_banner.php');?>
<?php 
use yii\helpers\Url;
use yii\helpers\Html;
?>
 <div class="index_banner">
	 <?php //= Html::img("@web/statics/images/banner.jpg", ['class'=>'pc_banner']);?>
	 <?php //= Html::img("@web/statics/images/m_banner.jpg", ['class'=>'m_banner']);?>
 </div>
         <?=$content ?>

<?php $this->endContent();?>

<!-- <div class="footer"> <div><strong>Copyright</strong> <?= Yii::$app->name?></div></div> -->
<div class="footer"> 
  	<p>会员管理系统  版权所有</p>
</div>
</body>


 