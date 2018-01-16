<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ReportMsg */

$this->title = Yii::t('app', 'Create Report Msg');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Report Msgs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-msg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
