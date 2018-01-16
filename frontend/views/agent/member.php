<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\Parameter;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '已开通会员';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'username',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            // 'email:email',
            // 'role',
            // 'status',
            'created_at:dateTime',
            // 'updated_at',
            // 'is_lock',
            [
                'label' => '所属服务中心',
                'attribute' => 'shop.username',
            ],
            [
                'label' => '激活状态',
                'attribute' => 'activate',
                'value' => function($model) {
                    return $model->activate > 0 ? '已激活' : '未激活';
                },
            ],
            [
                'attribute' => 'u_level',
                'value' => function($model) {
                    return Parameter::getUlevelName($model->u_level);
                },
            ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
