<?php 
use yii\web\Html;
use yii\helpers\Url;
use common\widgets\JsBlock;
$this->title = '首页';
$this->registerCssFile('@web/statics/css/ionicons.min.css');
$this->registerCssFile('@web/statics/themes/quirk/css/morris.css');
$this->registerJsFile('@web/statics/themes/quirk/js/jquery.js');
$this->registerJsFile('@web/statics/themes/quirk/js/raphael.js');
$this->registerJsFile('@web/statics/themes/quirk/js/morris.js');
?>

<div class="row">  
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3><?= isset($bonus['all_bonus']) ? $bonus['all_bonus'] : '0.00'?></h3>

        <p>奖金总额</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars"></i>
      </div>
      <a href="<?= Url::to(['bonus/index-all']) ?>" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->

  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green">
      <div class="inner">
        <h3><?= isset($recharge_money) ? $recharge_money : '0.00'?></h3>

        <p>充值总额</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars"></i>
      </div>
      <a href="<?= Url::to(['recharge/index']) ?>" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->

  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3><?= isset($toCash_money['to_money']) ? $toCash_money['to_money'] : '0.00'?></h3>

        <p>提现总额</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars"></i>
      </div>
      <a href="<?= Url::to(['to-cash/index']) ?>" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->

  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
      <div class="inner">
        <h3><?= $user_number ?></h3>

        <p>会员总数</p>
      </div>
      <div class="icon">
        <i class="ion ion-person-add"></i>
      </div>
      <a href="<?= Url::to(['member/index']) ?>" class="small-box-footer">详情 <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
</div>

<div class="row">
<section class="col-lg-12 connectedSortable ui-sortable">
  <div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
      <li class="pull-left header"><i class="fa fa-inbox"></i> 出纳 </li>
    </ul>
    <div class="tab-content no-padding">
      <!-- Morris chart - Sales -->
      <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
      <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
    </div>
  </div>
</section>
</div>

<?php JsBlock::begin() ?>
<script type="text/javascript">
    $(document).ready(function(){
        'use strict';
        var area = new Morris.Area({
            element   : 'revenue-chart',
            resize    : true,
            behaveLikeLine: true,
            data      : <?= json_encode($Array)?>,
            xkey      : 'today_time',
            ykeys     : ['item2', 'item1'],
            labels    : ['收入', '支出'],
            lineColors: ['#a0d0e0', '#3c8dbc'],
            hideHover : 'auto'
        });
    });
</script>
<?php JsBlock::end() ?>