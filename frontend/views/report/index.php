<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '反馈列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            // 'content:ntext',
            'create_time:datetime',
            // 'status',
            [
                'label'=>'消息',
                'value'=>function($model){
                    return $model->is_read == $model::NOHUIFU ? '待回复' : '已回复';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons'=>[
                    'view'=>function ($url,$model)
                    {
                        return Html::a('<span class="btn btn-success">'.Yii::t('app', '查看').'</span>', $url, [
                            'title' => Yii::t('app', '查看'),
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
