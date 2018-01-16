<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\models\Parameter;
use common\widgets\JsBlock;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '开通会员';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        "options" => ["class" => "grid-view","style"=>"overflow:auto", "id" => "grid"],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'class'=>'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                        return [];
                    }
            ],
            // 'id',
            'username',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            // 'email:email',
            // 'role',
            // 'status',
            'created_at:dateTime',
            'cpzj', //注册金额
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

      <?= Html::button('开通会员',['class' => 'btn btn-success', 'data-id'=>'audit']) ?>
      <?php //= Html::button('消费积分开通会员',['class' => 'btn btn-success', 'data-id'=>'audit2']) ?>
      <?= Html::button('删除会员',['class' => 'btn btn-danger', 'data-id'=>'del']) ?>

</div>
<br />
<!-- <p class="text-danger">消费积分开通会员为 ：50%消费积分+50%注册积分</p> -->
<p class="text-danger">注册积分余额：<?= $member->account->account4?></p>
<!-- <p class="text-danger">消费积分余额：<?= $member->account->account3?></p> -->

 <?php JsBlock::begin(); ?>

    <script type="text/javascript">
        $('.btn').on('click', function () {
            var key = $("#grid").yiiGridView("getSelectedRows");
            var val = $(this).attr('data-id');
            var html = $(this).html();
            var url = '<?=  Url::to(['batch']) ?>';
            // var csrfToken = $('meta[name="csrf-token"]').attr("content");
            layer.confirm('是否要 '+html, {
                    btn: ['是','否'] //按钮
                },
                function(){
                    $.post(url,{id:key,val:val},function(data){
                        if (data.status) {
                            layer.msg(data.msg,{icon:1},function(){
                                window.location.reload();
                            },'json');
                        }else{
                            layer.msg(data.msg,{icon:2});
                        }
                        console.log(data);

                    });
                });
        });
    </script>
<?php JsBlock::end();  ?> 
