<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Cat;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '新闻管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '发布新闻'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            // 'content:ntext',
            // 'label_img',
            // 'cat_id',

            [
            'attribute' => 'is_show',
                'label' => '发布状态',
                'value' => function($model) {
                    return $model->is_show == 0 ? '非置顶' : '置顶';
                },
                'filter' => [
                    0 => '非置顶',
                    1 => '置顶'
                ]
            ],
            [
            'attribute' => 'is_top',
                'label' => '发布状态',
                'value' => function($model) {
                    return $model->is_top == 0 ? '非置顶' : '置顶';
                },
                'filter' => [
                    0 => '非置顶',
                    1 => '置顶'
                ]
            ],
            [
                'attribute'=>'create_at',
                'value'=>function($data){
                    return date('Y-m-d H:i:s',$data->create_at);
                },
            ],
            [
                'attribute'=>'updated_at',
                'value'=>function($data){
                    return date('Y-m-d H:i:s',$data->create_at);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
