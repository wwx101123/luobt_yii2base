<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\ApAgent;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ApAgentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '申请服务中心');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ap-agent-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '点击申请'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <br />
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           
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
              
                return ApAgent::getState($model->state);
             },
              'filter' => ApAgent::getStates(),
            ],
        ],
    ]); ?>
</div>
