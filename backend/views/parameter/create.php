<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Parameter */

$this->title = 'Create Parameter';
$this->params['breadcrumbs'][] = ['label' => 'Parameters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
