<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member;
use common\models\Report;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '反馈列表');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-index">
    <p>
        <?= Html::a('发送反馈', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'title',
            // 'content:ntext',
            'member.username',
            [
                'attribute' => 'create_time',
                'value' => function ($data) {
                    return date('Y-m-d H:i:s', $data->create_time);
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    return Report::$status_arr[$data->status];
                }
            ],
            [   
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} &nbsp; {close}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if ($model->is_read == Report::NOHUIFU) {
                            $text = '(未回复)';
                        } else {
                            $text = '(已回复)';
                        }
                        return Html::a('查看'. $text, $url);
                    },
                    'close'=>function ($url, $model) {
                        return Html::a('关闭', $url);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
