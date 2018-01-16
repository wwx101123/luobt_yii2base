<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Parameter;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '服务中心列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            // 'username',
            [
                'attribute' => 'username',
                'value' => function ($model) {
                    return $model->username. ' ['. Html::a("登陆", ['frontend-login', 'uid' => $model->id], ['target' => '_blank']). ']';
                },
                'format'=>'raw',
            ],
            [
                'attribute' => 'shop.username',
                'label' => '所属服务中心',
                'value' => function ($model) {
                    return $model::getMemberName($model->shop_id);
                }
            ],
            'memberInfo.name',
            'memberInfo.phone',
            [
                'attribute' => 'activate',
                'label' => '激活时间',
                'value' => function ($model) {
                    return $model->activate > 0 ? date('Y-m-d H:i:s', $model->activate) : '未激活';
                }

            ],
            [
                'attribute' => 'u_level',
                'label' => '会员级别',
                'value' => function ($model) {
                    return Parameter::getUlevelName($model->u_level);
                },
            ],
            [
                'attribute' => 'g_level',
                'label' => '代理级别',
                'value' => function ($model) {
                    return Parameter::getGlevelName($model->g_level). \yii\bootstrap\Html::a(' 修改', '#', ['class' => 'j-edit', 'data-toggle' => 'modal', 'data-target' => '#page-modal']);
                },
                'format'=>'raw',
            ],
            'account.account3',
            'account.account4',
            'account.account5',
            'account.account6',
            'account.account7',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            // 'email:email',
            // 'role',
            // 'status',
            // 'created_at',
            // 'updated_at',
            // 'is_lock',
            // 'shop_id',
            // 'activate',
            // 'u_level',
            // 'dan',
            [
                'attribute' => 'is_agent',
                'label' => '是否为服务中心',
                'value' => function($model){
                    $str = $model->is_agent == 0 ? '否' : '是';
                    return $str . \yii\bootstrap\Html::a(' 修改', '#', ['class' => 'agent-edit', 'data-toggle' => 'modal', 'data-target' => '#page-modal']);
                },
                'format'=>'raw',
            ],
            // 'cpzj',
            // 'g_level',

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php
//模态提交代码
$upglevelUrl = yii\helpers\Url::toRoute(['upglevel']);
$agentUrl  = yii\helpers\Url::toRoute(['up-ag']);
$js = <<<JS
    fn('.j-edit','{$upglevelUrl}', '', '设置代理级别');
    fn('.agent-edit','{$agentUrl}', '', '设置服务中心');
JS;
$this->registerJs($js);
?>
