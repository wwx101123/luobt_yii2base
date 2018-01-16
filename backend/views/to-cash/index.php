<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member;
use common\models\ToCash;
use common\widgets\JsBlock;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ToCashSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '提现管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="to-cash-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        "options" => ["style"=>"overflow:auto", "id" => "grid"],
        'columns' => [
             [
            'class' => 'yii\grid\CheckboxColumn',
            'name' => 'id',
            ],
            [
                'attribute'=>'member_id',
                'value' => function($data){
                    return Member::getMemberName($data->member_id);
                }
            ],
            'bankname',
            'number',
            'username',
            'address',
            'to_money',
            'tax',
            'real_money',
            [
              'attribute'=>'type',
              'value' =>function($data){
                return ToCash::getTypeName($data->type);
              }
            ],
            'create_time:datetime',
            [
                'attribute'=>'confirm_time',
                'value'=>function($model){
                    return $model->confirm_time==0?' ':date('Y-n-j H:i:s',$model->confirm_time);
                }
            ],
            [
            'attribute' => 'state',
            'content' => function ($model) {
              
                return ToCash::getState($model->state);
             },
              'filter' => ToCash::getStates(),
            ],
        ],
    ]); ?>
</div>
    <?= Html::a('审核', "javascript:void(0);", ['class' => 'btn btn-success gridview','id'=>'audi']) ?>
    &nbsp;&nbsp;&nbsp;
    <?= Html::a('批量打回', "javascript:void(0);", ['class' => 'btn btn-danger gridvie','id'=>'delete']) ?>


 <?php JsBlock::begin(); ?>
<script type="text/javascript">

$(document).on('click', '#delete', function () {
    var key = $("#grid").yiiGridView("getSelectedRows");
    var url = '<?=  Url::to(['to-cash/refuse']) ?>';
layer.confirm('是否要打回', {
  btn: ['是','否'] //按钮
}, 
function(){
 $.post(url,{id:key},function(data){
      if (data.code) {
        layer.msg(data.msg,{icon:1},function(){
          window.location.reload();
        },'json');
      }else{
        layer.msg(data.msg,{icon:2});
      }
      console.log(data);
    }); 
});
});

$(document).on('click', '#audi', function () {
    var key = $("#grid").yiiGridView("getSelectedRows");
    var url = '<?=  Url::to(['to-cash/audit']) ?>';
layer.confirm('是否要审核', {
  btn: ['是','否'] //按钮
}, 
function(){
 $.post(url,{id:key},function(data){
      if (data.code) {
        layer.msg(data.msg,{icon:1},function(){
          window.location.reload();
        },'json');
      }else{
        layer.msg(data.msg,{icon:2});
      }
      console.log(data);
    }); 
});
});
</script>
<?php JsBlock::end();  ?>   