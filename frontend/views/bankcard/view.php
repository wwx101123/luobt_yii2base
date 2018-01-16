<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Bankcard */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '银行卡管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bankcard-view">

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'member_id',
            'bankname',
            'number',
            'province',
            'city',
            'address',
            'username',
        ],
    ]) ?>

</div>
