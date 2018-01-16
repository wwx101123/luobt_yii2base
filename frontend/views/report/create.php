<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = Yii::t('app', '提交反馈');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '反馈列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
