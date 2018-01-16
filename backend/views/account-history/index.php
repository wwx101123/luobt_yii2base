<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '货币流向管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-history-index">

   <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            [
             'attribute'=>'member_id',
             'label'=>'会员编号',
              'value' => function($model){
                    return Member::getName($model->member_id);
                }
            ],
            'amount',
            'account',
            'bz',
            'created_at:dateTime',
        ],
    ]); ?>
</div>
