<?php

use yii\helpers\Html;
use yii\helpers\Url; 
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '发件箱';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">
    <?php echo $this->render('_outsearch', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'fuid',
            // 'tuid',
            // 'fusername',
            'tusername',
            // 'title',
            ['attribute'=>'title',
                'value' => function($model){
                    return Html::a(Html::decode($model->title), Url::to(['message/outboxview', 'id'=>$model->id]));
                },
                'format' => 'raw',
            ],
            // 'content:ntext',
            // 'rdt',
            ['attribute' => 'f_read',
                'value' => function($model){
                    return $model->f_read == 0 ? '未阅' : '已阅';
                },
            ],
            [
                'label'=>'操作',
                'value' =>function($data){
                    return Html::a('点击阅读', Url::to(['message/outboxview', 'id'=>$data->id]),['class'=>'btn btn-info btn-sm']);
                },
                'format' => 'raw',
            ],
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
