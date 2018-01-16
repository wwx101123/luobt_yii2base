<?php
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member;

/* @var $this yii\web\View */
/* @var $searchModel common\models\IncomeRecordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '当期收入记录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="income-record-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            'create_time:datetime',
            [
                'attribute' => 'user_id',
                'label' => '会员编号',
                'value' => function ($data) {
                    return Member::getMemberName($data->user_id);
                }
            ],
            'money',
            'bz:ntext',
        ],
    ]); ?>
</div>
