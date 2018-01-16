<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\AccountTransfer;
use common\models\Member;
/* @var $this yii\web\View */
/* @var $searchModel common\models\AccountTransferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '转账申请');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-transfer-index">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'type',
                'value' =>function($data){
                    return AccountTransfer::getType($data->type);
                }
            ],
            [
                'attribute' =>'out_name',
                'value' =>function($data){
                    return Member::getMemberName($data->out_id);
                }
            ],
            [
                'attribute' =>'into_name',
                'value' =>function($data){
                    return Member::getMemberName($data->into_id);
                }
            ],
            'out_money',
            'into_money',

            'info:ntext',
            'create_time:datetime',

        ],
    ]); ?>
</div>
