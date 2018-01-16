<?php 
$this->title = "接点关系图";
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\JsBlock;
use common\models\Tree;
?>
<?php 
    $this->registerCssFile('@web/statics/css/tree-style.css?v=0.14');
?>
<div class="row">
        <?php $form = ActiveForm::begin(['method'=>'get', 'action'=>Url::to(['/tree/tree'])]); ?>
          <div class="col-lg-3 pull-left">
            <div class="input-group">
              <input type="text" class="form-control" name="username" placeholder="会员编号">
              <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">查询</button>
              </span>
            </div><!-- /input-group -->
          </div><!-- /.col-lg-6 -->
        <?php ActiveForm::end(); ?>
          <div class="col-lg-2">
            <select name="level" class="form-control" onchange="window.location.href='<?php echo Url::to(['/tree/tree', 'lev'=>'']) ?>'+this.value;">
            <?php for ($i = 2; $i <= 5; $i++): ?>
              <?php if ($i == $lev): ?>
                <option value="<?php echo $i ?>" selected="selected">第 <?php echo $i ?> 层</option>
              <?php else: ?>
                <option value="<?php echo $i ?>">第 <?php echo $i ?> 层</option>
              <?php endif ?>
            <?php endfor ?>
            </select>
          </div><!-- /.col-lg-6 -->
          <div class="col-lg-2 text-left">
            　　<a href="<?php echo Url::to(['/tree/tree','lev'=>$lev]); ?>">顶层</a>　　<a href="<?php echo Url::to(['/tree/tree','lev'=>$lev, 'username'=>$fatherUsername]); ?>">上一层</a>
          </div><!-- /.col-lg-6 -->
        </div>
        <div class="bs-component table-responsive">


    <div class="relation_box">
        <div class="box">
            <?php foreach ($models as $key => $vals): ?>
                <div class="row text-center">
                <?php if ($key == 0): ?>
                    <?php // 这里是顶点 ?>
                    <div style="width: 100%">
                        <?php echo Html::img(Url::to('@web/statics/images/tree/tree'.$vals->member->u_level.'.png'),['user-data'=>Tree::getTipJsonData($vals), 'class'=>'shopInfoImg']); ?>
                      <p>
                        <?php echo $vals->member->activate > 0 ? $vals->member->username : $vals->member->username . '<span>[未激活]</span>'; ?>
                      </p>
                    </div>
                  <?php else: ?>   
                    <?php $w = 100 / count($vals); ?>
                    <!-- 第一个和最后一个不要，后面两个并一个 -->
                      <?php for ($i=0; $i< count($vals)*2; $i++): ?>
                          <?php if ($i == 0 || $i == count($vals)*2 - 1): ?>
                            <div style="width: <?php echo $w/2 ?>%;float: left;height:64px;"></div>
                            <?php elseif($i % 2 == 0 && $i): ?>
                              <?php if ($i % 4 == 0): ?>
                                <div style="height:64px;width: <?php echo $w; ?>%;float: left;"></div>
                                  <?php else: ?>
                                  <div class="box_inbox02" style="height:64px;width: <?php echo $w; ?>%;float: left;">
                                     <div class="inbox_left_2"></div>
                                     <div class="inbox_right_2"></div>
                                     <div class="clear"></div>
                                   </div>
                              <?php endif ?>
                          <?php endif ?>
                      <?php endfor; ?>
                    
                    <?php // 这里是每个子点位 ?>
                    <?php foreach ($vals as $k => $u): ?>

                      <div style="float: left;width: <?php echo $w ?>%">
                        <?php if (isset($u['member_id'])): ?>
                          <?php echo Html::img(Url::to('@web/statics/images/tree/tree'.$u->member->u_level.'.png'),['user-data'=>Tree::getTipJsonData($u), 'class'=>'shopInfoImg']); ?>
                          <p>
                            <?php echo Html::a($u->member->activate > 0 ? $u->member->username : $u->member->username . '<span>[未激活]</span>', Url::to(['tree/tree','username'=>$u->member->username, 'lev'=>$lev])); ?>
                          </p>
                        <?php else: ?>
                            <?php if ($u && !isset($u->member)): ?>
                                <?php echo Html::img(Url::to('@web/statics/images/tree/register.png')); ?>
                                <p>
                                  <?php echo Html::a('点击注册',Url::to(['signup/index','father_id'=>$u, 'tp'=>$k%2])); ?>
                                </p>
                              <?php else: ?>

                                <?php echo Html::tag('p','空位'); ?>
                            <?php endif ?>
                        <?php endif ?>
                      </div>

                    <?php endforeach ?>
                <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>
    </div>
  
<div id="tip" style="display: none;">
  <div class="tool_tip_wrap">
      <div class="tool_tip_head">
          <div class="row col-sm-12 text-center">
            <img id='tip-img' src="">
          </div>
          <div id='tip-username' class="row col-sm-12 text-center" style="padding-bottom: 10px;"></div>
          <hr>
          <div class="row col-sm-12">
              <div class="col-sm-6">昵称：<span id='tip-nickname'></span></div>
              <!-- <div class="col-sm-6">级别：<span id='tip-u_level'></span></div> -->
          </div>
          <div class="row col-sm-12">
              <div class="col-sm-6">左区：<span id='tip-l'></span></div>
              <div class="col-sm-6">右区：<span id='tip-r'></span></div>
          </div>
          <div class="row col-sm-12">
              <div class="col-sm-6">左余：<span id='tip-sl'></span></div>
              <div class="col-sm-6">右余：<span id='tip-sr'></span></div>
          </div>
      </div>
  </div>
</div>
    <hr> 
    <div class="row ">
      <div class="col-md-offset-3">
        <?php foreach ($ulevel as $key => $value): ?>        
          <div class="panel panel-tile text-center br-a br-light col-sm-2">
            <div class="panel-body bg-light">
              <img src="<?php echo $value['img'] ?>" height="37" width="37">
              <h6 class="text-system"><?php echo $value['str'] ?></h6>
            </div>
          </div>   
        <?php endforeach ?>
      </div>
</div></div>
<?php $this->registerJSFile('@web/statics/js/plugins/tooltip/tooltip.js', ['depends' => ['frontend\assets\AppAsset'], 'position' => $this::POS_HEAD]) ?>
 <?php  JsBlock::begin()?>
<script type="text/javascript">
  $('.shopInfoImg').on('mouseover', function () {
    info = JSON.parse($(this).attr('user-data'));
    $("#tip-img").attr('src', '<?php echo Url::to('@web/statics/images/tree/tree'); ?>' + info.u_level + '.png');
    $("#tip-username").html(info.username);
    $("#tip-nickname").html(info.nick_name);
    // $("#tip-u_level").html(info.u_level);
    $("#tip-l").html(info.l);
    $("#tip-r").html(info.r);
    $("#tip-sl").html(info.sl);
    $("#tip-sr").html(info.sr);
    tooltip.show($('#tip').html());
  });
  $('.shopInfoImg').on('mouseout', function () {
    tooltip.hide();
  });
</script>

<?php  JsBlock::end()?>