<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\widgets\modal\ModalWidget;
use yii\base\Widget;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '分类管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cat-index">

    <p>
        <?= Html::a('创建新闻类别', ['create'], ['class' => 'btn btn-success j-add', 'data-toggle' => 'modal', 'data-target' => '#page-modal']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons'=>[
                    'update' => function ($url, $model, $key){
                        return \yii\bootstrap\Html::a('编辑', '#', ['class' => 'j-edit', 'data-toggle' => 'modal', 'data-target' => '#page-modal']);
                    },
                
                    'delete'=> function ($url, $model, $key){
                        return  Html::a('删除', ['delete', 'id'=>$model->id],[
                            'data-method'=>'post',
                            'data-confirm' => '确定删除该项？',
                        ] ) ;
                    }
                ],
            ],
        ],
    ]); ?>
</div>

<?php
//模态提交代码
$createUrl = yii\helpers\Url::toRoute(['create']);
$updateUrl = yii\helpers\Url::toRoute(['update']);
$js = <<<JS
    fn('.j-add','{$createUrl}','','创建新闻类别'); 
    fn('.j-edit','{$updateUrl}', '', '编辑');
JS;
$this->registerJs($js);
?>
