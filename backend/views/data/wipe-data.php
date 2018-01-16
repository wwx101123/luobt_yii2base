<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $model common\models\Parameter */

$this->title = Yii::t('app', '清空数据');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parameters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-create">
    <br>
    <div class="text-center text-danger">清空数据后只能通过已备份好的数据进行还原，如未备份将无法还原当前数据!</div>
    <br>
    <br>
    <div class="row text-center">
        <?php echo Html::submitButton('清空数据',['class'=>'btn btn-danger', 'id'=>'wipe-btn']); ?>
    </div>
</div>

<?php JsBlock::begin(); ?>
<script type="text/javascript">
    $("#wipe-btn").on('click',function(){
        if (!confirm("清空数据后只能通过已备份好的数据进行还原，如未备份将无法还原当前数据，是否已备份好数据并确定清空当前数据？")) {
            return false;
        }
        $(this).removeClass('btn-danger');
        $(this).html('数据正在清空，请稍后……');
        $.post({
            dataType:"json",
            url:'<?= Url::to(['wipe-data-ajax']);?>',
            data:{id:1},
            success:function(data){
                window.location.reload();
            }
        });
    });
</script>
<?php JsBlock::end(); ?>