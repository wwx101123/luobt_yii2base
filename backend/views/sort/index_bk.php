<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SortSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '分类管理');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sort-index">



    <p>
        <?= Html::a(Yii::t('app', '添加分类'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'parent_id',
            'sort_name',
            'sort_order',
            [
                'attribute'=>'attach_thumb',
                'value'=>function ($data)
                {
                    return Html::img($data->attach_thumb,['width'=>50]);
                },
                'format'=>'raw',
            ],
            'menu_is',
            [
                'attribute'=>'status_is',
                'value'=>function ($data)
                {
                    return \common\models\Sort::getStatus()[$data->status_is];
                }
            ],

            // 'big_id',
            // 'grade',
            // 'filter_attr',
            // 'p_path:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
