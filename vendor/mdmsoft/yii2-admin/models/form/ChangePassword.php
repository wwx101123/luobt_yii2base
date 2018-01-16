<?php

namespace mdm\admin\models\form;

use Yii;
use mdm\admin\models\User;
use yii\base\Model;

/**
 * Description of ChangePassword
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ChangePassword extends Model
{
    public $oldPassword;
    public $newPassword;
    public $retypePassword;
    public $grade;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'retypePassword','grade'], 'required'],
            [['oldPassword'], 'validatePassword'],
            [['newPassword'], 'string', 'min' => 6],
            [['retypePassword'], 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }
    public function attributeLabels(){
        return[
            'oldPassword' =>'原密码',
            'newPassword' =>'新密码',
            'retypePassword' =>'确认新密码',
            'grade' => '修改类型',
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        /* @var $user User */
        $user = Yii::$app->user->identity;
        if($this->grade==1){
            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError('oldPassword', '原密码错误.');
            }
        }else{
            if (!$user || !$user->validatePasswordTwo($this->oldPassword)) {
                $this->addError('oldPassword', '原密码错误.');
            }
        }
    }

    /**
     * Change password.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function change()
    {
        if ($this->validate()) {
            /* @var $user User */
            $user = Yii::$app->user->identity;
            if($this->grade==1){
                $user->setPassword($this->newPassword);
            }else{
                $user->setPasswordTwo($this->newPassword);
            }
            $user->generateAuthKey();
            if ($user->save()) {
                return true;
            }
        }

        return false;
    }
}
