<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Member;

/**
 * Login form
 */
class UpdatePassword extends Model
{
    public $grade;
    public $oldpassword;
    public $newpassword;
    public $conpassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['oldpassword', 'newpassword','conpassword'], 'required'],
            ['conpassword', 'compare', 'compareAttribute'=>'newpassword'],
            [['oldpassword', 'newpassword','conpassword'], 'string'],
            [['oldpassword'],'checkOldPwd'],
            [['grade'],'integer'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'oldpassword' => '原密码',
            'newpassword' => '新密码',
            'conpassword' => '确认新密码',
            'grade'=>'密码类型',
        ];
    }
    public function checkOldPwd(){
        $model=Member::find()->where(['id'=>Yii::$app->user->identity->id])->one();
        if($this->grade==1){
        $info=$model->validatePassword($this->oldpassword);
        if(!$info){
            return $this->addError('oldpassword', '原一级密码错误');
        }
        $model->setPassword($this->newpassword);
        $model->generateAuthKey();
        $model->save();  
        }else{
            $info=$model->validatePasswordTwo($this->oldpassword);
            if(!$info){
                return $this->addError('oldpassword', '原二级级密码错误');
            }
            $model->setPasswordTwo($this->newpassword);
            $model->generateAuthKey();
            $model->save();  
        }      

    }

}












