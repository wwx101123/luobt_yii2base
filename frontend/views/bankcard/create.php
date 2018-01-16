<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Bankcard */

$this->title = '添加银行卡';
$this->params['breadcrumbs'][] = ['label' => 'Bankcards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bankcard-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
