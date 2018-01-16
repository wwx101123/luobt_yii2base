<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = '登录';

$fieldOptions1 = [
    'inputTemplate' => "{input}"
];

$fieldOptions2 = [
    'inputTemplate' => "{input}"
];
?>

<div class="box_bg" id="loginHeight">
  
  
     <div class="login_logo">
        <div class="container">
        
       <img src="<?= Url::to('@web')?>/statics/images/logo.png" alt="" class="login_logo_img"/>
            </div>
         </div>   
       
<div class="container">
        
        
       <div class="login_bg">
          
          
              <div class="tab tablogin">

    <div class="tabt">
    
        <span id="t1" class="current">用户登录</span>
        
        <div class="clear"></div>
        
    </div>
    <div class="t1" style="display:block">
    
    <!--             <p class="name_p">用户名</p>
     <p class="name_p">用户名</p>
     <input type="text" class="name_input" placeholder="请输入用户名">
     <p class="name_p">密码</p>
     <input type="password" class="name_input" placeholder="请输入登录密码">
        <p class="name_p">验证码</p>
     <input type="password" class="name_input" placeholder="请输入验证码" style="width:40%;">

     <input type="button" class="login_btn" value="登录"> -->

     <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
            <p class="name_p">用户名</p>

            <?= $form
                ->field($model, 'username', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => '请输入用户名', 'class' => 'name_input']) ?>
            <p class="name_p">密码</p>
            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' =>  '请输入登录密码', 'class' => 'name_input']) ?>
            <p class="name_p">验证码</p>
            <?= $form->field($model,'verifyCode')->widget(Captcha::className(), [
                                'template' => '{input}<em class="yzm_em">{image}</em>','options'=>['class'=>'name_input yzm_input','placeholder'=>'请输入验证码']])->label(false);?>

            <div class="row">
                <div class="col-xs-8">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
                <?= Html::submitButton('登录', ['class' => 'login_btn']) ?>
            </div>
            <?php ActiveForm::end(); ?>
     
    </div>


  
</div>
          
          
         
          
          
          </div>
        
        
        
        </div>
     
     

       
  
  
</div>




<script type="text/javascript">
<!-- 
var winWidth = 0;
var winHeight = 0;
function findDimensions() //函数：获取尺寸
{
//获取窗口宽度
if (window.innerWidth)
winWidth = window.innerWidth;
else if ((document.body) && (document.body.clientWidth))
winWidth = document.body.clientWidth;
//获取窗口高度
if (window.innerHeight)
winHeight = window.innerHeight;
else if ((document.body) && (document.body.clientHeight))
winHeight = document.body.clientHeight;
//通过深入Document内部对body进行检测，获取窗口大小
if (document.documentElement  && document.documentElement.clientHeight && document.documentElement.clientWidth)
{
winHeight = document.documentElement.clientHeight;
winWidth = document.documentElement.clientWidth;
}
//结果输出至两个文本框
//document.form1.availHeight.value= winHeight;
//document.form1.availWidth.value= winWidth;
var loginheight = document.getElementById("loginHeight");
    loginheight.style.height = winHeight + 'px';
}
findDimensions();
//调用函数，获取数值
window.onresize=findDimensions;
//-->
</script>
