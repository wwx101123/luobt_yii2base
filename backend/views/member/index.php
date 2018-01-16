<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Parameter;
use common\widgets\JsBlock;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '会员列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php if (!isset($activate)): ?>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php endif; ?>

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
                    return $model->username. ' ['.Html::a("登陆", ['frontend-login', 'uid' => $model->id], ['target' => '_blank']). ']';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'shop.username',
                'label' => '所属服务中心',
                'value' => function ($model) {
                    return $model::getMemberName($model->shop_id);
                }
            ],
            ['attribute' => 'relationship.re_id',
                'label' => '推荐人',
                'value' => function ($model) {
                    return $model::getMemberName($model->relationship->re_id);
                },
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
                    return Parameter::getGlevelName($model->g_level) . \yii\bootstrap\Html::a(' 修改', '#', ['class' => 'j-edit', 'data-toggle' => 'modal', 'data-target' => '#page-modal']);
                },
                'format' => 'raw',
            ],
            'account.account3',
            'account.account4',
            'account.account5',
            // 'account.account6',
            // 'account.account7',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            // 'email:email',
            // 'role',
            // 'status',
            // 'created_at',
            // 'updated_at',
            [
                'attribute' => 'is_lock',
                'label' => '锁定',
                'value' => function ($model) {
                    $str = $model->is_lock == 0 ? '否' : '是'; //账号锁定只能从后台登录，前台无法登录
                    return $str . \yii\bootstrap\Html::a(' 修改', yii\helpers\Url::to(['lock', 'id' => $model->id]));
                },
                'format' => 'raw',
            ],
            // 'shop_id',
            // 'activate',
            // 'u_level',
            // 'dan',
            [
                'attribute' => 'is_agent',
                'label' => '是否为服务中心',
                'value' => function ($model) {
                    $str = $model->is_agent == 0 ? '否' : '是';
                    return $str . \yii\bootstrap\Html::a(' 修改', '#', ['class' => 'agent-edit', 'data-toggle' => 'modal', 'data-target' => '#page-modal']);
                },
                'format'=>'raw',
            ],
            [
                'label' => '修改资料',
                'value' => function ($model) {
                    return \yii\bootstrap\Html::a('修改', '#', ['class' => 'agent-update', 'data-toggle' => 'modal', 'data-target' => '#page-modal']);
                },
                'format'=>'raw',
            ],
            [
                'label' => '重置密码',
                'value' => function ($model) {
                    return \yii\bootstrap\Html::Button('重置', ['class' => 'btn btn-info reset', 'data-id' => $model->id]);
                },
                'format' => 'raw',
            ],
            // 'cpzj',
            // 'g_level',
            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php JsBlock::begin(); ?>
<?php
//模态提交代码
$upglevelUrl = yii\helpers\Url::toRoute(['upglevel']); // 代理级别修改
$agentUrl  = yii\helpers\Url::toRoute(['up-ag']); // 是否设置为服务中心
$updateMemberUrl = yii\helpers\Url::toRoute(['update-info']); // 修改资料
$js = <<<JS
    fn('.j-edit','{$upglevelUrl}', '', '编辑'); 
    fn('.agent-edit','{$agentUrl}', '', '编辑');
    fn('.agent-update','{$updateMemberUrl}', '', '编辑');
JS;
$this->registerJs($js);
?>

<script type="text/javascript">
    $('.reset').on('click', function () {  
        var val = $(this).attr('data-id'); //获取对应的记录id
        var url = '<?=  Url::to(['reset-password']) ?>';
        layer.confirm('重置一级密码为：111111，二级密码为：222222', {
                btn: ['是','否'] //按钮
            }, function () {
                $.post(url, {id: val}, function (data) {
                    if (data.code) {
                        alert('xxx');
                        layer.msg(data.msg, {icon: 1}, function () {
                            window.location.reload();
                        }, 'json');
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                    console.log(data);
                });
            });
    });
</script>
<?php JsBlock::end(); ?>
