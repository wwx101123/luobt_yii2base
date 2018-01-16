<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Bonus;
use common\models\Member;
use yii\helpers\Url;
use common\widgets\JsBlock;
use common\models\IncomeRecord;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BonusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '拨出比率');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonus-index">

    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showFooter' => true,
        'columns' => [
            'today_time:datetime',
            [
                'attribute' => 'b_all',
                'label' => '当期拨出',
                'value' => function ($model) {
                    return $model->b_all . ' [' . Html::a("查询", ['/bonus/index-all', 'today_time' => $model->today_time]) . ']';
                }, 
                'format' => 'raw',
            ],
            [
                'attribute' => 'income',
                'label' => '当期收入',
                'value' => function ($model) {
                    return IncomeRecord::getIncome($model->today_time) . '[' . Html::a("查询", ['/income-record/index', 'activate' => $model->today_time]) . ']';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'profit',
                'label' => '本期赢利',
                'value' => function ($model) {
                    return IncomeRecord::getIncome($model->today_time) - $model->b_all;
                },
            ],
            [
                'attribute' => 'ratio',
                'label' => '拨出比率',
                'value' => function ($model) {
                    return IncomeRecord::getRatio($model->today_time, $model->b_all);
                }
            ],
        ],
    ]); ?>
</div>

<?php  JsBlock::begin()?>
<script>
$(document).ready(function () {
    var array=['累计',0,0,0,0];
    $('tbody').find("tr").each(function(i,o){
         $(this).find('td').each(function(k,j){
           
                if (k>0 && k<=3) {
                    if (k==1|| k==2) {
                    value = $(this).html().split('[');
                    array[k] += Number(value[0]);
                }else{
                    array[k] += Number($(this).html());
                }
            }
         })
        // console.log(array);
})
    $('tfoot').find('td').each(function(i,o){

        if (i==4) {
            ratio = array[1]/array[2]*100;
            $(this).text(ratio.toFixed(1)+' %');
        }else if (i>0 && i<=3) {
            $(this).text(parseFloat(array[i]).toFixed(2));
        }else{
            $(this).text(array[i]);
        }
    })
})

</script>
<?php  JsBlock::end()?>