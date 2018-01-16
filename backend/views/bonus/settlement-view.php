<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\Widgets\JsBlock;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Parameter */

$this->title = Yii::t('app', '结算分红');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-create">
    
    <div class="text-danger text-center">结算前请先做好数据备份！！！</div><br /> 
    
    <div class="text-center">
        <div>累计业绩：<?= $yeji?></div><br />
        
        <div>实发奖金：<?= $yeji * 0.3?> (累计业绩的30%)</div><br />
        
        <div>总计份数：<?= $amount?> 份</div><br />
        
        <div>实结奖金：<input type="text" name="amount" class="amount" value="<?= $yeji * 0.3?>"></div><br />
        
        <div class="btn btn-success" id="backupbtn">结算分红</div>
    </div>

    <div style="display: none;" id="pro">
    
        <div class="h5" id="status-str">正在初始化</div>

        <div class="progress progress-striped active">
            <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar progress-bar-primate" id="progress">
                <span class="sr-only"></span>
            </div>
        </div>
    </div>

</div>

<?php JsBlock::begin(); ?>
<script type="text/javascript">
$ (function() {
    var maxLimit = 0;
    var limit = 0;
    var oneLimit = 5;
    $("#backupbtn").on('click', function(){
        if (!confirm("结算前请先做好数据备份，结算过程中请不要关闭浏览器，结算时间由数据大小决定，是否确定开始结算")) {
            return false;
        }
        $(window).bind('beforeunload', function () {return '结算过程中请不要关闭浏览器，否则将会导致结算错误'});
        $(this).hide();
        // 显示状态区域
        $("#pro").show();
        // 获取表数据
        setStatusStr("正在初始化...");
        start();
    });

    function start() {
        // layer.load(2,{shade: [0.3,'#fff']});
        $.post({
            dataType:"json",
            data:{oneLimit:oneLimit},
            url:'<?= Url::to(['settlement-fenhong-info']);?>',
            success:function(data){
               // layer.closeAll('loading');
                if (data.code == 1) {
                    maxLimit = data.msg;
                    backupTable(0);
                }
                else {
                    alert(data.msg);
                    window.location.reload();
                }
            }
        });
    }

    function backupTable(index) {
        var amount = $('.amount').val();
        if (amount <= 0) {
            setTimeout('done()',1000);
            return;
        }
        limit = index;
        setPro();
        if (limit >= maxLimit) {
            setTimeout('done()',1000);
            return;
        }
        setStatusStr("正在结算("+ limit + "/" + maxLimit+ ")");
        $.post({
            dataType:"json",
            url:'<?= Url::to(['settlement']);?>',
            data:{index:limit, oneLimit:oneLimit, amount:amount},
            success:function(data){
                if (data.code == 1) {
                    backupTable(Number(limit) + Number(data.msg));
                }
                else {
                    alert(data.msg);
                    window.location.reload();
                }
            }
        });
    }

    function setPro() {
        var pr = limit / maxLimit * 100;
        $("#progress").attr('style', 'width:'+pr+'%');
    }

    function setStatusStr(str) {
        $("#status-str").html(str);
    }

});

function done(){
    $(window).unbind('beforeunload');
    alert("结算完成");
    window.location.reload();
}
</script>
<?php JsBlock::end(); ?>