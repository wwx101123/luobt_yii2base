<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Member;
use common\models\Parameter;
/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['verifyCode', 'captcha'],//注意这里，在百度中查到很多教程，这里写的都不一样，最 简单的写法就像我这种写法，当然还有其它各种写法 
            ['password', 'validatePassword'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'rememberMe' => '记住登录状态',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        $switch = Parameter::getFrontendSwitch();
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                return $this->addError($attribute, '用户名或密码错误');
            }
            if($switch==1){
                return $this->addError('password', '服务器正在维护更新');

            }
            if ($user->is_lock == 1) {
                return $this->addError('password', '账户已经锁定');
            }

            if ($user->activate == 0) {
                return $this->addError('username', '此会员未激活');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Member::findByUsername($this->username);
        }

        return $this->_user;
    }
}
