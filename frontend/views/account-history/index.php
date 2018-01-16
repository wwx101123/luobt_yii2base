<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AccountHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '账户流水';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-history-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'member_id',
            'created_at:dateTime',
            'amount',
            'account',
            'bz',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
