<?php

use yii\helpers\Html;
use yii\helpers\Url; 
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '收件箱';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">
    <?php echo $this->render('_insearch', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'fuid',
            // 'tuid',
            'fusername',
            'tusername',
            // 'title',
            ['attribute'=>'title',
                'value' => function($model){
                    return Html::a(Html::decode($model->title), Url::to(['message/inboxview', 'id'=>$model->id]));
                },
                'format' => 'raw',
            ],
            'content:ntext',
            'rdt:datetime',
            ['attribute' => 't_read',
                'value' => function($model){
                    return $model->t_read == 0 ? '未阅' : '已阅';
                },
            ],

             [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{inboxview} {inboxdelete}',
            'buttons' => [

                'inboxview' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'View'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                },
               
                'inboxdelete' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'Delete'),
                        'aria-label' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ];
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                },
            ]
         ],
        ],
    ]); ?>
</div>
