<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member;
use common\models\AccountChange;
/* @var $this yii\web\View */
/* @var $searchModel common\models\AccountChangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '积分转换');
$this->params['breadcrumbs'][] = $this->title;
?>  
<div class="account-change-index">
  <?= $this->render('_form', [
    'model' => $model,
     'account' => $account,
      'accountModel' => $accountModel,
  ]) ?>
  <div class="clear"></div> 
</div>

<div class="row">  
    <h4 class="reg_txt">转换记录</h4>          
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
              'attribute'=>'type',
              'value' =>function($data){
                return AccountChange::getMoneyType($data->type);
              },
              'filter'=>AccountChange::getTypes(),
            ],
            'money', 
            'old_money',
            'new_money',
            'create_time:datetime',
        ],
    ]); ?>
</div>