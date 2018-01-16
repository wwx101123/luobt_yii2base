<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Report */

$this->title = Yii::t('app', '发送反馈');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
