<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Bankcard */

$this->title = 'Update Bankcard: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bankcards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bankcard-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
