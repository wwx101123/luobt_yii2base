<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Bonus;
use common\models\Parameter;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BonusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '奖金明细');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonus-index">
<?php 
    $columnsBegin = ['today_time:datetime'];
    $columnsBonus = [];
    $nameArr = Parameter::getBonusNameArr([1]);
    foreach ($nameArr as $key => $name) {
        $index = $key + 1;
        $bName = 'b'.$index;
        $columnsBonus[] = ['label'=>$name,'attribute'=>$bName];
    }
    $columnsBonus[] = ['label'=>'合计', 'attribute'=>'b_all'];
    $columnsEnd = [[
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons' => [
                    'view'=>function ($url,$model)
                    {
                        return Html::a('查看明细', yii\helpers\Url::to(['view','today_time'=>$model->today_time]), [
                'title' => Yii::t('app', '查看明细'),]);
                    }
                ]
            ]];
    $columns = array_merge_recursive($columnsBegin, $columnsBonus, $columnsEnd);
?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>
</div>
