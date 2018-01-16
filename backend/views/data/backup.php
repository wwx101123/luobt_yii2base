<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\JsBlock;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Parameter */

$this->title = Yii::t('app', '数据库备份');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parameters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-create">
    
    <table  class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>备份名</th>
            <th>操作</th>
        </tr>
    </thead>
        <?php foreach ($list as $key => $name): ?>
            <tr>
                <td><?php echo $name.'<br/>'; ?></td>
                <td>
                    <?php echo Html::Button('还原', ['class'=>'btn btn-danger','data-name'=>$name, 'name'=>'btn-reback']); ?>
                    <?php echo Html::Button('删除', ['class'=>'btn','data-name'=>$name, 'name'=>'btn-del']); ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
    <div class="text-center">
        <div class="btn btn-success" id="backupbtn">备份数据</div>
    </div>



</div>
<div style="display: none;" id="pro">
    
    <div class="h5" id="status-str">正在初始化</div>
    <div class="progress progress-striped active">
        <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar progress-bar-primate" id="progress">
            <span class="sr-only"></span>
        </div>
    </div>
</div>


<?php JsBlock::begin(); ?>
<script type="text/javascript">
$(function(){

    var tableList;
    var tableAmount=0;
    var limit = 0;
    var maxLimit = 0;
    var oneLimit = 1500;
    var dir = '';
    $("#backupbtn").on('click', function(){
        if (!confirm("备份数据过程中请不要关闭浏览器，备份时间由数据大小决定，是否确定开始备份")) {
            return false;
        }
        $(this).hide();

        // 显示状态区域
        $("#pro").show();
        // 获取表数据
        setStatusStr("正在初始化...");
        start();
    });

    function start() {
        $.post({
            dataType:"json",
            url:'<?= Url::to(['get-db-info']);?>',
            data:{id:1},
            success:function(data){
                if (data.status == 1) {
                    tableInfo = data.data;
                    tableList = tableInfo.tables;
                    limit = 0;
                    maxLimit = tableInfo.amount;
                    tableAmount = tableList.length;
                    dir = tableInfo.dir;
                    backupTable(0, 0);
                }
                else {
                    alert(data.info);
                    window.location.reload();
                }
            }
        });
    }

    function backupTable(index, tbIndex) {
        setPro();
        var les = tbIndex + 1;
        var tb = tableList[tbIndex];
        if (!tb) {
            setTimeout('backupDone()',1000);
            return;
        }
        setStatusStr("正在备份表数据("+les+"/"+tableAmount+")");
        $.post({
            dataType:"json",
            url:'<?= Url::to(['create-table-data']);?>',
            data:{dir:dir, tb:tableList[tbIndex], index:index, limit:oneLimit},
            success:function(data){
                if (data.status == 1) {
                    reData = data.data;
                    var amount = reData.amount;
                    limit += amount;
                    if (amount < oneLimit) {
                        backupTable(0, tbIndex+1);
                    }
                    else {
                        backupTable(index+amount, tbIndex);
                    }
                }
                else {
                }
            }
        });
    }

    function rollback(str) {
        setStatusStr("备份失败，"+str+",正在删除不完整的备份信息");
        $.post({
            dataType:"json",
            url:'<?= Url::to(['rollback']);?>',
            data:{dir:dir},
            success:function(data){
                alert(data.info);
                window.location.reload();
            }
        });
    }

    function setStatusStr(str) {
        $("#status-str").html(str);
    }

    function setPro() {
        var pr = limit / maxLimit * 100;
        $("#progress").attr('style', 'width:'+pr+'%');
    }

});

function backupDone(){
    alert("数据库备份完成");
    window.location.reload();
}

var isHy = false;
$(function(){
    var backupFieldList;
    var fieldAmount = 0;
    var fieldIndex = 0;
    var dataFieldIndex = 0;
    var allDataFieldIndex = 0;
    var fdir;
    var dataFieldAmount = 0;
    $('button[name="btn-reback"]').on('click', function(){
        
        if (isHy) {
            // alert("正在还原备份，请稍后……");
            return;
        }
        if (!confirm('还原操作会将当前数据将被替换，是否确定？')) {
            return;
        }
        isHy = true;
        $("#backupbtn").hide();
        // 显示状态区域
        $("#pro").show();
        $("#status-str").html("正在初始化...");
        // 获取表数据
        var dir = $(this).attr('data-name');
        $.post({
            dataType:"json",
            url:'<?= Url::to(['get-sql-files']);?>',
            data:{dir:dir},
            success:function(data){
                // alert(data.info);
                dataFieldAmount = data.info;
                backupFieldList = data.data;
                fieldAmount = backupFieldList.length;
                fieldIndex = 0;
                dataFieldIndex = 0;
                allDataFieldIndex = 0;
                fdir = dir;
                excuteSql();
            }
        });
    });

    function setPro() {
        var pr = (allDataFieldIndex+1) / dataFieldAmount * 100;
        $("#progress").attr('style', 'width:'+pr+'%');
        $("#status-str").html("正在还原数据("+(allDataFieldIndex+1)+"/"+dataFieldAmount+")...");

    }

    function excuteSql() {
        setPro();
        var f = backupFieldList[fieldIndex];
        if (!f) {
            return;
        }
        $.post({
            dataType:"json",
            url:'<?= Url::to(['exec-sql']);?>',
            data:{f:f, fdir:fdir, index:dataFieldIndex},
            success:function(data){
                if (data.info == 'ok') {
                    fieldIndex++;
                    dataFieldIndex = 0;
                    allDataFieldIndex++;
                }
                else {
                    dataFieldIndex++;
                    allDataFieldIndex++;
                }
                console.log(fieldAmount);
                if (fieldIndex >= fieldAmount) {
                    setTimeout('reBackDone()',1000);
                    return;
                }
                else{
                    excuteSql();
                }
            }
        });
    }

});

// 删除
    $("button[name='btn-del']").on('click', function(){
        if (!confirm('是否删除此备份文件？')) {
            return false;
        }
        var dir = $(this).attr('data-name');
        $.post({
            dataType:"json",
            url:'<?= Url::to(['del-backup-dir']);?>',
            data:{dir:dir},
            success:function(data){
                alert(data.info);
                window.location.reload();
            }
        });
    });
function reBackDone(){
    alert("数据还原完成");
    isHy = false;
    window.location.reload();
}
</script>
<?php JsBlock::end(); ?>