<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ParameterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '参数设置';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?= Html::a('Create Parameter', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            // 'val',
            [
                'attribute'=>'val',
                'label'=>'值',
                'value' => function($model){
                    return $model->val . \yii\bootstrap\Html::a('【修改】', '#', ['class' => 'j-edit', 'data-toggle' => 'modal', 'data-target' => '#page-modal']);
                },
                'format'=>'raw',

            ],
            'explain',
            // 'hidden',
            // 'show_type',
            // 'sort_num',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php
//模态提交代码
$upValUrl = yii\helpers\Url::toRoute(['update-val']);
$js = <<<JS
    fn('.j-edit','{$upValUrl}', '', '编辑');
JS;
$this->registerJs($js);
?>
