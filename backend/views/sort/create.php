<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Sort */

$this->title = Yii::t('app', '添加分类');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '分类管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sort-create">


    <div class="col-lg-7">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
    <div class="col-lg-5">
    </div>
</div>
