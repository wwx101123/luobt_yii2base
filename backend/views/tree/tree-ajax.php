<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\JsBlock;
use common\models\Parameter;
use common\models\Member;
use common\models\Relationship;
use common\models\Tree;
use backend\models\LocomotionUser;
$this->title = '推荐关系图';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.treep1{display:;}
.treep2{display:none;}
p{margin:0px}
</style>

  <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-3 pull-left">
          <div class="input-group">
            <input type="text" class="form-control" name="username" placeholder="会员编号" >
            <span class="input-group-btn">
              <button class="btn btn-primary" type="submit">查询</button>
            </span>
          </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
        <div class="col-lg-3">
          团队人数 : <b><?php echo $teamAmount ?></b>
        </div><!-- /.col-lg-6 -->
      </div><!-- /.row -->
  <?php ActiveForm::end(); ?>


        <?php $form = ActiveForm::begin(['action'=>Url::to(['/tree/tree-ajax'])]); ?>
          <div class="col-lg-3 pull-left">
            <div class="input-group">
            <?= $form->field($UserModel, 'move_user')->textInput() ?>
            <?= $form->field($UserModel, 'to_user')->textInput() ?>
              <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">移动</button>
              </span>
            </div><!-- /input-group -->
          </div><!-- /.col-lg-6 -->
        <?php ActiveForm::end(); ?>

  <br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?= Html::img(Tree::getAjaxTreeImg($topMember), ['name'=>"img1".$topMember->id, 'align'=>'absmiddle']);?>
          <?php echo $topMember->username; ?>
          [<?php echo Parameter::getUlevelName($topMember->u_level); ?>][<?php echo Parameter::getGlevelName($topMember->g_level); ?>]

    </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="mm">
  <tbody><tr>
    <td>
    <?php foreach ($users as $key => $user): ?>
      <?php 
        $imgUrl = Url::to('@web/statics/images/tree');
        $imgNamePrefix = Relationship::find()->where(['re_id'=>$user->id])->one() ? 'P' : 'L'; 
        $imgNameNum = $key == count($users) - 1 ? 2 : 1;
        $imgFullUrl = $imgUrl . '/' . $imgNamePrefix . $imgNameNum . '.gif';
        $ppath = $key == count($users) - 1 ? "" : "1";
        $openImage = $imgUrl . '/M' . $imgNameNum . '.gif';
       ?>
        <div>
            <img id="img<?php echo $user->id;?>" src="<?php echo $imgFullUrl ?>" align="absmiddle" 
            onclick="openmm('m<?php echo $user->id;?>','img<?php echo $user->id;?>','<?php echo $user->id;?>','1','<?php echo $ppath ?>')">
            <?= Html::img(Tree::getAjaxTreeImg($user), ['id'=>"fg".$user->id, 'align'=>'absmiddle']);?>
            <?php echo $user->username; ?>

          [<?php echo Parameter::getULevelName($user->u_level); ?>][<?php echo Parameter::getGlevelName($user->g_level); ?>]
  
            <img id="oimg<?php echo $user->id;?>" src="<?php echo $openImage ?>" align="absmiddle" style="display:none;">
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="m<?php echo $user->id;?>" class="treep2">
          <tbody><tr>
            <td id="m<?php echo $user->id;?>_tree"><img src="<?php echo Url::to('@web/statics/images/tree/L4.gif') ?>" align="absmiddle"><img src="<?php echo Url::to('@web/statics/images/tree/loading2.gif') ?>" align="absmiddle"></td>
          </tr>
          </tbody>
        </table>
    <?php endforeach ?>

    </td>
  </tr>
</tbody>
</table>
<div class="row ">
          <?php foreach (Tree::$ajaxStrArr as $key => $value): ?>
                <div class="panel panel-tile text-center br-a br-light col-sm-2">
                  <div class="panel-body bg-light">
                    <?= Html::img($key)?>
                    <h6 class="text-system"><?php echo $value ?></h6>
                  </div>
                </div>
          <?php endforeach ?>
</div>

<?php JsBlock::begin(); ?>
<script>
function openmm(oid,tid,mid,numm,ppath){
  var tobj = document.getElementById(oid);
  var mobj = document.getElementById(tid);
  var cmid = "o"+tid;
  var cobj = document.getElementById(cmid);
  var coimg = cobj.src;
  if(tobj.className=="treep2"){
    tobj.className="treep1";
    var opppid = oid+"_tree";
    ajaxChech(opppid,mid,numm,ppath)
  }else{
    tobj.className="treep2";
  }
  cobj.src = mobj.src;
  mobj.src = coimg;
  

}
function ajaxChech(vid,aid,nnn,pp){
  var xmlHttp;
  try{
    //FF Opear 8.0+ Safair
    xmlHttp=new XMLHttpRequest();
  }
  catch(e){
    try{
      xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    catch(e){
      alert("{:L('您的浏览器不支持')}AJAX");
      return false;    
    }
  }
  xmlHttp.onreadystatechange=function(){
    if(xmlHttp.readyState==4){
      var valuet = xmlHttp.responseText;
      document.getElementById(vid).innerHTML=valuet;
    }
  }
  var url="<?php echo Url::to(['tree/get-ajax-son']); ?>";
  url+="?reid="+aid+"&nn="+nnn+"&pp="+pp;
  xmlHttp.open("GET",url,true);
  xmlHttp.send(null);
}
</script>
<?php JsBlock::end(); ?>
