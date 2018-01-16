<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Sort */

$this->title = Yii::t('app', '修改 {modelClass}: ', [
    'modelClass' => '分类',
]) .$model->sort_name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '分类'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sort_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', '修改');
?>
<div class="sort-update">

    <!-- <h3><?= Html::encode($this->title) ?></h3>  -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
