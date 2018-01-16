<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Address */

$this->title = '添加收货地址';
// $this->params['breadcrumbs'][] = ['label' => 'Addresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
           <div class='col-lg-9'>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>
