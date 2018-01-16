<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member;
/* @var $this yii\web\View */
/* @var $searchModel common\models\BankcardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '银行卡管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bankcard-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
        <?= Html::a('添加银行卡', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('去提现', ['to-cash/index'], ['class' => 'btn btn-info btn-sm']) ?>
    </p>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'会员编号',
                'value' =>function($data){
                    return Member::getMemberName($data->member_id);
                },
            ],


            'bankname',
            ['attribute' => 'number',  'visible' => false],

            'number',
            'province',
            'city',
            'address',
            'username',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
