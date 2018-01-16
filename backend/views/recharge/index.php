<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Recharge;
use common\models\Member;
use common\models\Account;
use common\widgets\JsBlock;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\RechargeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '充值中心');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recharge-index">

    <?php  echo $this->render('_form', ['model' => $model]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => "yii\grid\CheckboxColumn",
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return [];
                }
            ],
            // [
            //     'attribute' => 'member_id',
            //     'value' => function ($data) {
            //         return Member::getMemberName($data->member_id);
            //     },
            //     'filter' => function ($model) {
            //         Member::searchMember($model->member_id);
            //     },
            // ],
            [
                'attribute' => 'member.username',
                'label' => '会员名称',
                'value' => function ($data) {
                    return Member::getMemberName($data->member_id);
                },
                'filter' => function ($model) {
                    Member::searchMember($model->member_id);
                },
                'filter' => Html::activeTextInput($model, 'username', [
                    'class' => 'form-control', 'id' => null
                ]),
            ],
            [
                'attribute' => 'type',
                'value' => function ($data) {
                    return Account::$name_array[$data->type];
                },
                'filter' => Account::$name_array,

            ],
            're_money',
            'create_time:datetime',
            [
                'attribute' => 'pay_time',
                'value' => function ($model) {
                    return $model->pay_time == 0 ? '' : date('Y-n-j H:i:s', $model->pay_time);
                }
            ],
            [
                'attribute' => 'pay_type',
                'value' => function ($model) {
                    return $model->pay_type == 0 ? '后台充值' : Recharge::$pay_type[$model->pay_type];
                },
                'filter' => Recharge::$pay_type,
            ],
            [
                'attribute' => 'confirm_time',
                'value' => function ($model) {
                    return $model->confirm_time == 0 ? '' : date('Y-n-j H:i:s', $model->confirm_time);
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
            //'account.',
        ],
    ]); ?>
    <p>
        <?= Html::a('审核确认', "javascript:void(0);", ['class' => 'btn btn-success gridview', 'id' => 'audit']) ?>
        <?= Html::a('删除', "javascript:void(0);", ['class' => 'btn btn-danger gridvie','id' => 'delete']) ?>
    </p>

</div>

<?php JsBlock::begin(); ?>
<script type="text/javascript">
    $(document).on('click', '#delete', function () {
        var key = $("#grid").yiiGridView("getSelectedRows"); 
        var url = '<?= Url::to(['recharge/refuse']); ?>';
        layer.confirm('是否要删除', {
            btn: ['是','否'] //按钮
        }, 
        function () {
            $.post(url, {id: key}, function (data) {
              if (data.code) {
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

    $(document).on('click', '#audit', function () {
        var key = $("#grid").yiiGridView("getSelectedRows");
        var url = '<?= Url::to(['recharge/audit']); ?>';
        layer.confirm('是否要审核', {
            btn: ['是','否'] //按钮
        }, 
        function () {
            $.post(url, {id:key}, function (data) {
              if (data.code) {
                layer.msg(data.msg, {icon:1}, function () {
                    window.location.reload();
                }, 'json');
              } else {
                    layer.msg(data.msg, {icon:2});
              }
              console.log(data);
            }); 
        });
    });
</script>
<?php JsBlock::end(); ?>


