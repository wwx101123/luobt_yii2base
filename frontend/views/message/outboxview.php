<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = '发邮件';
$this->params['breadcrumbs'][] = $this->title;
$this->params['showBackBtn'] = true;
?>
<div class="message-index">
    <div><?php echo Html::encode($model->title) ?></div>
    
    <div><?php echo Html::encode($model->content) ?></div>
</div>
