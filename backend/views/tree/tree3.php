<?php 
$this->title = "网络图";
$this->params['breadcrumbs'][] = $this->title;

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\JsBlock;
use common\models\Tree;
use backend\models\LocomotionUser;
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




        <?php $form = ActiveForm::begin(['action'=>Url::to(['/tree/tree'])]); ?>
          <div class="col-lg-3 pull-left">
            <div class="input-group">
            <?= $form->field($UserModel, 'move_user')->textInput() ?>
            <div class="row">
              <div class=" col-lg-9">
            <?= $form->field($UserModel, 'to_user')->textInput() ?>
              </div>
              <div class=" col-lg-3">

            <?= $form->field($UserModel, 'area')->dropDownList(LocomotionUser::$arr) ?>
            </div>
            </div>
              <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">移动</button>
              </span>
            </div><!-- /input-group -->
          </div><!-- /.col-lg-6 -->
        <?php ActiveForm::end(); ?>







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
                       <?php if ($vals->member->activate > 0): ?>
                            <?php if ($vals->member->is_agent==1): ?>
                              <?php echo $vals->member->username.'(<font color="#FF0000">服务中心</font>)' ?>
                            <?php else: ?>
                              <?php echo $vals->member->username?>
                            <?php endif ?>
                       <?php else: ?>
                        <?php echo  $vals->member->username . '<span>[未激活]</span>'?>
                       <?php endif ?>
                      </p>
                    </div>
                  <?php else: ?>   
                    <?php $w = 100 / count($vals); ?>
                    <?php echo Tree::getLine(count($vals), 3); ?>
                    
                    <?php // 这里是每个子点位 ?>
                    <?php foreach ($vals as $k => $u): ?>
                      <?php $tpl = $k%3; ?>
                      <div style="float: left;width: <?php echo $w ?>%">
                      
                        <?php if (isset($u['member_id'])): ?>
                          <?php echo Html::img(Url::to('@web/statics/images/tree/tree'.$u->member->u_level.'.png'),['user-data'=>Tree::getTipJsonData($u), 'class'=>'shopInfoImg']); ?>
                          <p>
                          <?php if ($u->member->activate>0): ?>
                              <?php if ($u->member->is_agent==1): ?>
                                 <?php echo Html::a($u->member->username.'(<font color="#FF0000">服务中心</font>)', Url::to(['tree/tree','username'=>$u->member->username, 'lev'=>$lev])); ?>
                              <?php else: ?>
                                    <?php echo Html::a($u->member->username, Url::to(['tree/tree','username'=>$u->member->username, 'lev'=>$lev])); ?>
                              <?php endif ?>
                          <?php else: ?>
                            <?php echo Html::a($u->member->username . '<span>[未激活]</span>', Url::to(['tree/tree','username'=>$u->member->username, 'lev'=>$lev])); ?>
                          <?php endif ?>
                          </p>
                          </a>
                        <?php else: ?>
                            <?php if ($u && !isset($u->member)): ?>
                                <a href="<?php echo Url::to(['signup/index','father_id'=>$u, 'tp'=>$k%3]) ?>">
                                <?php echo Html::img(Url::to('@web/statics/images/tree/register.png')); ?>
                                <p>
                                  
                                  <?php echo Html::a('点击注册',Url::to(['signup/index','father_id'=>$u, 'tp'=>$k%3])); ?>

                                </p>
                              </a>
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
      <div class="tool_tip_head" >
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
            业绩
          </div>
          <div class="row col-sm-12">
              <div class="col-sm-4">左区：<span id='tip-l'></span></div>
              <div class="col-sm-4">中区：<span id='tip-m'></span></div>
              <div class="col-sm-4">右区：<span id='tip-r'></span></div>
          </div>
<!--           <div class="row col-sm-12">
              <div class="col-sm-4">左余：<span id='tip-sl'></span></div>
              <div class="col-sm-4">中余：<span id='tip-sm'></span></div>
              <div class="col-sm-4">右余：<span id='tip-sr'></span></div>
          </div> -->
<!--           <div class="row col-sm-12">
            重复消费
          </div>
          <div class="row col-sm-12">
              <div class="col-sm-4">左区：<span id='xf-l'></span></div>
              <div class="col-sm-4">中区：<span id='xf-m'></span></div>
              <div class="col-sm-4">右区：<span id='xf-r'></span></div>
          </div> -->
<!--           <div class="row col-sm-12">
              <div class="col-sm-4">左余：<span id='xf-sl'></span></div>
              <div class="col-sm-4">中余：<span id='xf-sm'></span></div>
              <div class="col-sm-4">右余：<span id='xf-sr'></span></div>
          </div> -->
      </div>
  </div>
</div>
       <hr> 
    <div class="row ">
      <div class="col-lg-12">
        <?php foreach ($ulevel as $key => $value): ?>        
          <div class="panel panel-tile text-center br-a br-light col-sm-1">
            <div class="panel-body bg-light">
              <img src="<?php echo $value['img'] ?>" height="37" width="37">
              <h6 class="text-system"><?php echo $value['str'] ?></h6>
            </div>
          </div>   
        <?php endforeach ?>
      </div>
</div>
</div>
<?php $this->registerJSFile('@web/statics/js/plugins/tooltip/tooltip.js', ['depends' => ['backend\assets\AppAsset'], 'position' => $this::POS_HEAD]) ?>
 <?php  JsBlock::begin()?>
<script type="text/javascript">
  $('.shopInfoImg').on('mouseover', function () {
    info = JSON.parse($(this).attr('user-data'));
    $("#tip-img").attr('src', '<?php echo Url::to('@web/statics/images/tree/tree'); ?>' + info.u_level + '.png');
    $("#tip-username").html(info.username);
    $("#tip-nickname").html(info.nick_name);
    // $("#tip-u_level").html(info.u_level);
    $("#tip-l").html(info.l);
    $("#tip-m").html(info.r);
    $("#tip-r").html(info.lr);
    // $("#tip-sl").html(info.now_l);
    // $("#tip-sm").html(info.now_mid);
    // $("#tip-sr").html(info.now_r);
    // $("#xf-l").html(info.xf_l_num);
    // $("#xf-m").html(info.xf_mid_num);
    // $("#xf-r").html(info.xf_r_num);
    // $("#xf-sl").html(info.xf_l_last);
    // $("#xf-sm").html(info.xf_mid_last);
    // $("#xf-sr").html(info.xf_r_last);
    tooltip.show($('#tip').html());
  });
  $('.shopInfoImg').on('mouseout', function () {
    tooltip.hide();
  });
</script>

<?php  JsBlock::end()?>