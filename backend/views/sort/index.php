<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SortSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '分类管理');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sort-index">



    <p>
        <?= Html::a(Yii::t('app', '添加分类'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>排序</th>
            <th>名称</th>
            <th>操作</th>
        </tr>
        </thead>
        <?php foreach ($model as $k => $v): ?>
        <tr>
            <td><?php echo $v['id'] ?></td> 
            <td><?php echo $v['sort_order'] ?></td> 
            <td> <?= Html::a('<i class="fa fa-plus-circle" aria-hidden="true"></i>',['create','id'=>$v['id']]) ?> <?php echo $v['sort_name'] ?></td> 
            <td> 
             <?= Html::a('修改',['update','id'=>$v['id']]); ?>     
                | 
            <?= Html::a('删除',['delete','id'=>$v['id']] ,['data-method'=>'post']); ?>  
                
            </td> 
        </tr>    
               
        <?php endforeach ?>
    </table>
</div>
