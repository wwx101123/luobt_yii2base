<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Parameter;
use common\models\Member;
/* @var $this yii\web\View */
/* @var $searchModel common\models\BonusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '详细');
$this->params['breadcrumbs'][] = ['label'=>'奖金明细','url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bonus-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'pager'=>[
             'options'=>['class'=>'hidden'],
            ],
        'columns' => [
            
           
            'create_time:datetime',
            [
                'attribute'=>'account_type',
                'value'=>function ($data)
                {
                    return Parameter::getBonusName($data->bonus_type-1);
                },

            ],
            // [
            //     'attribute'=>'reg_id',
            //     'value'=>function ($data)
            //     {
            //         return Member::getName($data->reg_id);
            //     },

            // ],

            'amount',
            'bz',
            'clear_time:datetime',
            // 'start_money',
            // 'end_money',
        ],
    ]); ?>
</div>

<?= common\widgets\LinkPager::widget([ 
  'pagination' => $dataProvider->pagination,
]); ?>