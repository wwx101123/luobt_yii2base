<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member;
use common\models\ApAgent;
use common\widgets\JsBlock;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '服务中心申请');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ap-agent-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        "options" => ["style" => "overflow:auto", "id" => "grid"],
        'columns' => [
            [
              'class' => 'yii\grid\CheckboxColumn',
              'name' => 'id',
            ],
            [
            	'attribute' => 'member_id',
            	'value' => function ($data) {
            		return Member::getMemberName($data->member_id);
            	}
            ],
            'create_time:datetime',
            [
              'attribute'=>'confirm_time',
              'value' => function ($model) {
                  return $model->confirm_time == 0 ? ' ' : date('Y-n-j H:i:s', $model->confirm_time);
              }
            ],
            [
              'attribute' => 'state',
              'content' => function ($model) {    
                  return ApAgent::getState($model->state);
              },
              'filter' => ApAgent::getStates(),
            ],
        ],
    ]); ?>
</div>
<div class="row">
  &nbsp;&nbsp;&nbsp;&nbsp;<?= Html::a('审核', "javascript:void(0);", ['class' => 'btn btn-success gridview', 'id' => 'audi']) ?>
  &nbsp;&nbsp;
  <?= Html::a('批量打回', "javascript:void(0);", ['class' => 'btn btn-danger gridvie','id'=>'delete']) ?>
</div>

<?php JsBlock::begin(); ?>
<script type="text/javascript">
    //批量打回
    $(document).on('click', '#delete', function () {
        var key = $("#grid"). yiiGridView("getSelectedRows");
        if (key == false) {
            alert('请选择记录');
            return false;
        }
        var url = '<?=  Url::to(['ap-agent/refuse']) ?>';
        layer.confirm('是否要打回', { btn: ['是', '否'] }, function () {
            $.post(url, {id: key}, function (data) {
                if (data.code) {
                    layer.msg(data.msg, {icon: 1}, function () {
                      window.location.reload();
                    }, 'json');
                } else {
                    layer.msg(data.msg, {icon: 2});
                }
                console.log(data);
            }); 
        });
    });

    //审核
    $(document).on('click', '#audi', function () {
        var key = $("#grid"). yiiGridView("getSelectedRows");
        if (key == false) {
            alert('请选择记录');
            return false;
        }
        var url = '<?=  Url::to(['ap-agent/audit']) ?>';
        layer.confirm('是否要审核', {btn: ['是', '否']}, function () {
            $.post(url, {id: key}, function (data) {
                if (data.code) {
                    layer.msg(data.msg, {icon: 1}, function () {
                        window.location.reload();
                    }, 'json');
                } else {
                    layer.msg(data.msg, {icon: 2});
                }
                console.log(data);
            }); 
        });
    });
</script>
<?php JsBlock::end(); ?>   