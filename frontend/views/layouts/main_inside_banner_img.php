<?php $this->beginContent('@frontend/views/layouts/main_banner.php');?>
<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\Alert;
?>


<div class="index_banner">
 <?php //= Html::img("@web/statics/images/sub_banner.jpg", ['class'=>'index_banner']);?>
 <?php //= Html::img("@web/statics/images/m_banner.jpg", ['class'=>'m_banner']);?>
 </div>

 <!-- <style>
 @media (min-width: 768px){
.form-horizontal .control-label{padding-right: 15px;width: 20%;}
.form-horizontal .btn{margin-left: 20%;}
.form-horizontal .form-control,.form-horizontal .form-control-static{     display: inline-block;
    width: 30%;}
	.form-horizontal .help-block{ display:inline-block; width:45%;}
}
 </style> -->
<div class="wraper m-t">
    <?= Alert::widget() ?>
	<div class="panel panel-default">
	    <div class="panel-heading"><?= $this->title?></div>
	    <div class="panel-body">
	          <?=$content ?>
	    </div>
	</div>
</div>

<?php $this->endContent();?>
<div class="footer">
    
    <p>会员管理系统  版权所有</p>
   </div>
</body>


 