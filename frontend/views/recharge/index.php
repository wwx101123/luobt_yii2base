<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Recharge;
use common\models\Account;
use common\models\Parameter;
/* @var $this yii\web\View */
/* @var $searchModel common\models\RechargeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '充值中心');
$this->params['breadcrumbs'][] = $this->title;
?>
<!--   <div class="reg_txt bankinfo">
        线下汇款账号:
        <em style="color: #999"><?php echo Parameter::getValById(23) ?></em>
    </div> -->
<div class="recharge-index">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'type',
                'value' =>function($model){
                    return Account::$name_array[$model->type];
                },
              'filter' => Recharge::getReList(),
                
            ],
            're_money',
            'create_time:datetime',
            [
                'attribute'=>'confirm_time',
                'value'=>function($model){
                    return $model->confirm_time==0?' ':date('Y-n-j H:i:s',$model->confirm_time);
                }
            ],
            [
            'attribute' => 'state',
            'content' => function ($model) {
              
                return Recharge::getState($model->state);
             },
              'filter' => Recharge::getStates(),
            ],
            'info',

        ],
    ]); ?>
</div>
