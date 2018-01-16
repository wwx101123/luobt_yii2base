<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminLoginInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '管理员登录日志';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-login-info-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'uid',
            'username',
            'ip',
            'created_at:dateTime',
            // [
            // 'label'=>'是否登录成功',
            // 'value'=>function($model){
            //     return $model->uid == 0 ? '失败' : '成功';
            // },
            // ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
