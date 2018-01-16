<?php
use yii\helpers\Html;
?>
<div class="per_upload_con" data-url="<?=$config['serverUrl']?>">
    <div class="per_real_img <?=$attribute?>" domain-url = "<?=$config['domain_url']?>"><?=!empty($inputValue)?'<img src="'.$config['domain_url'].$inputValue.'">':''?></div>
    <div class="per_upload_img">图片上传</div>
    <div class="per_upload_text">
        <p class="upbtn" >
        <a id="<?=$attribute?>" href="javascript:;" class="btn btn-success green btn-post choose_btn">
        选择图片
        </a>
        </p>
        <p class="rule">仅支持文件格式为jpg、jpeg、png以及gif<br>大小在1MB以下的文件</p>
    </div>
    <input up-id="<?=$attribute?>" type="hidden" name="<?=$inputName?>" upname='<?=$config['fileName']?>' value="<?=isset($inputValue)?$inputValue:''?>" filetype="img" />
</div>