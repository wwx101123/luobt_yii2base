<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\ToCash;
use common\models\Account;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ToCashSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '提现申请');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a('银行卡管理',['bankcard/index'],['class'=>'btn m-b btn-primary'])?>
<br />
<br />


<div class="to-cash-index">
    <?= $this->render('_form', [
        'model' => $model,
        'account_model' => $account_model,
        //'account' => $account3,
    ])?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bankname',
            'number',
            'username',
            'address',
            'to_money',
            'tax',
            'real_money',
            [
                'attribute' =>'type',
                'value' =>function($model){
                    return Account::$name_array[$model->type];
                }
            ],
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
              
                return ToCash::getState($model->state);
             },
              'filter' => ToCash::getStates(),
            ],
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
