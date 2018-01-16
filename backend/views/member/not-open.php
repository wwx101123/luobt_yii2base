<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\widgets\JsBlock;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserWeixinSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '未开通会员';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-weixin-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <?= Html::a('添加会员', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        "options" => ["class" => "grid-view", "style"=>"overflow:auto", "id" => "grid"],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class'=>'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return [];
                }
            ],
            'username',
            [
                'label' => '推荐人',
                'value'=> function ($model) {
                    return $model::getMemberName($model->relationship->re_id);
                },
            ],
            [
                'attribute' => 'shop.username',
                'label' => '所属服务中心',
                'value' => function ($model) {
                    return $model::getMemberName($model->shop_id);
                }
            ],
            [
                'label' => '接点人',
                'value' => function($model) {
                    return  $model::getMemberName($model->relationship->father_id);
                },
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);   //主要通过此种方式实现 ????
                },
                'headerOptions' => ['width' => '150'], //设置宽度
            ],
            'cpzj',
            [
                'attribute' => 'created_at',
                'value'=> function($model) {
                    return  date('Y-m-d H:i:s', $model->created_at);   //主要通过此种方式实现
                },
                'headerOptions' => ['width' => '150'],
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update}', 'header' => '操作',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                            return Html::a('删除', $url, [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'method' => 'post',
                                ],
                            ]);
                        return null;
                    },
                ],
            ],
        ],
    ]);?>

    <?= Html::button('开通会员', ['class' => 'btn btn-primary button', 'data-id' => 'audit']) ?>

    <!-- <?= Html::button('开通空单', ['class' => 'btn btn-warning button', 'data-id' => 'empty']) ?> -->
</div>

<?php JsBlock::begin(); ?>
<script type="text/javascript">
    $('.button').on('click', function () {
        var key = $("#grid").yiiGridView("getSelectedRows");
        var val = $(this).attr('data-id');
        var url = '<?= Url::to(['batch']); ?>';

        layer.confirm('是否要审核', {btn: ['是', '否']}, function () {
            $.post(url, {id: key, val: val}, function (data) {
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
</script>
<?php JsBlock::end();  ?>