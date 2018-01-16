<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FenhongSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '广告收益';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fenhong-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [

            'qi',
            'money',
            'rdt:dateTime',
            'amount',
            'f_amount',
            'f_money',
            'dft:dateTime',
        ],
    ]); ?>
</div>
