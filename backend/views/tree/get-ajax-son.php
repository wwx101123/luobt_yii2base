<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use common\JsBlock\JsBlock;
use common\models\Member;
use common\models\Parameter;
use common\models\Relationship;
use common\models\Tree;
?>
<?php foreach ($users as $key => $user): ?>
    <p>
    <?php for ($i=1; $i < $nn; $i++): ?>
        <?php
            if (in_array($i, $pp)) {
                echo '<img src="'.Url::to('@web/statics/images/tree/L4.gif').'" align="absmiddle">';
            }
            else {
                echo '<img src="'.Url::to('@web/statics/images/tree/L5.gif').'" align="absmiddle">';
            }

        ?>
    <?php endfor ?>

    <?php 
        $imgUrl = Url::to('@web/statics/images/tree');
        $imgNamePrefix = Relationship::find()->where(['re_id'=>$user->id])->one() ? 'P' : 'L'; 
        $imgNameNum = $key == count($users) - 1 ? 2 : 1;
        $imgFullUrl = $imgUrl . '/' . $imgNamePrefix . $imgNameNum . '.gif';
        $cppath = '';
        $cppath = $key == count($users) - 1 ? $ppath : $ppath.",".$nn;
        $openImage = $imgUrl . '/M' . $imgNameNum . '.gif';
       ?>

            <img id="img<?php echo $user->id;?>" src="<?php echo $imgFullUrl ?>" align="absmiddle" 
            onclick="openmm('m<?php echo $user->id;?>','img<?php echo $user->id;?>','<?php echo $user->id;?>',<?php echo $nn; ?>,'<?php echo $cppath ?>')">
            <?= Html::img(Tree::getAjaxTreeImg($user), ['id'=>"fg".$user->id, 'align'=>'absmiddle']);?>
            
            <?php echo $user->username; ?>

          [<?php echo Parameter::getULevelName($user->u_level); ?>][<?php echo Parameter::getGlevelName($user->g_level); ?>]

            <img id="oimg<?php echo $user->id;?>" src="<?php echo $openImage ?>" align="absmiddle" style="display:none;">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="m<?php echo $user->id;?>" class="treep2">
          <tbody><tr>
            <td id="m<?php echo $user->id;?>_tree"><img src="<?php echo Url::to('@web/statics/images/tree/L4.gif') ?>" align="absmiddle"><img src="<?php echo Url::to('@web/statics/images/tree/loading2.gif') ?>" align="absmiddle"></td></tr></tbody></table>
      </tbody>
    </table>
<?php endforeach ?>