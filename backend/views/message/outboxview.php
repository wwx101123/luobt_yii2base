<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = '发件内容详情';
$this->params['breadcrumbs'][] = $this->title;
$this->params['showBackBtn'] = true;
?>

<table class='table table-striped table-bordered detail-view'>
    <tbody>
        <tr>
            <th style="width: 200px">发件人:</th>
            <td><?php echo Html::encode($model->fusername) ?></td>
        </tr>
         <tr>
            <th >收件人:</th>
            <td><?php echo Html::encode($model->tusername) ?></td>
        </tr>
        <tr>
            <th style="width: 200px">发件标题:</th>
            <td><?php echo Html::encode($model->title) ?></td>
        </tr>
         <tr>
            <th >发件内容:</th>
            <td><?php echo Html::encode($model->content) ?></td>
        </tr>
         <tr>
            <th >发件时间:</th>
            <td><?php echo Html::encode(date("Y/m/d",$model->rdt)) ?></td>
        </tr>
    </tbody>
</table>
<?= Html::a('返回',['outbox'],['class' => 'btn btn-info']) ?>
