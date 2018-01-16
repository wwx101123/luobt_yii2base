<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\Relationship;
use common\models\MemberInfo;
use common\models\Account;

/**
 * Member model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Member extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'created_at' => '注册时间',
            'shop_id' => '所属服务中心',
            'activate' => '是否已激活',
            'u_level' => '会员级别',
            'g_level' => '代理级别',
            'cpzj' => '注册金额',
            'is_agent' => '是否为服务中心',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['g_level','is_agent'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    //验证二级密码
    public function validatePasswordTwo($password_two)
    {
        if($this->password_hash_two){
            return Yii::$app->security->validatePassword($password_two, $this->password_hash_two);
        }
        return false;
    }

    /**
     * Generates password hash from password and sets it to the model
     * 一级密码
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password, 4);
    }

    //二级密码加密
    public function setPasswordTwo($password_two)
    {
        $this->password_hash_two = Yii::$app->security->generatePasswordHash($password_two, 4);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    // 服务中心(会员归属哪个服务中心)
    public function getShop()
    {
        return $this->hasOne(self::className(), ['id' => 'shop_id']);
    }

    public function getRelationship()
    {
        return $this->hasOne(Relationship::className(), ['member_id' => 'id']);
    }

    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['member_id' => 'id']);
    }

    public function getMemberInfo()
    {
        return $this->hasOne(MemberInfo::className(), ['member_id' => 'id']);
    }

    public function openUser($isPay = 0)
    {   
        if ($this->activate > 0) {
            throw new \Exception("不可重复激活", 1);   
        }
        $this->activate = strtotime(date('Y-m-d H:i:s'));
        if ($isPay == 2) {
            $this->dan = 0; // 开通空单
        }
        if (!$this->save()) {
            throw new \Exception("生成激活时间失败", 1);   
        }
        if ($isPay == 2) {
            return true;
        }
        // 添加会员激活数据，利用ID保证会员开通顺序是唯一的
        $model = new MemberIn;
        $model->member_id = $this->id;
        $model->created_at = time();
        if (!$model->save()) {
            throw new \Exception("插入会员开通顺序失败", 1);    
        }
        Relationship::updateAllCounters(['re_nums' => 1], ['member_id' => $this->relationship->re_id]);
        Relationship::addDuipengInfo($this->relationship->father_id, $this->relationship->area, $this->dan, $this->cpzj);
        IncomeRecord::writeToRecord($this->id, $this->cpzj, $bz = '开通会员');
        BonusCalc::calc($this, $isPay);
        return true;
    }

    public function beforeDelete()  
    {
        Account::deleteAll(['member_id' => $this->id]);
        Relationship::deleteAll(['member_id' => $this->id]);
        MemberInfo::deleteAll(['member_id' => $this->id]);
        return parent::beforeDelete();  
    }

    //返回会员编号
    public static function getMemberName($id)
    {
        $model = self::find()->where(['id' => $id])->one();
        if ($model) {
            return $model->username;
        }
        // throw new \Exception("Error Processing Request", 1);
        return NULL;   
    }

    //返回会员编号
    public static function getName($id)
    {
        $model = self::find()->where(['id' => $id])->one();
        if ($model) {
            return $model->username;
        }
        throw new \Exception("Error Processing Request", 1);
    }

    //返回姓名
    public static function getFatherName($id)
    {
        $model = self::find()->where(['username' => $id])->one();
        if($model){
            return $model->memberInfo->name; 
        }
        throw new \Exception("Error Processing Request", 1);
        
    }

    //开通会员总的注册金额
    public static function OpenMemberMoney($today_time)
    {
        $total = self::find()->where(['activate' => $today_time])->orderBy(['id' => SORT_ASC])->asArray()->sum('cpzj');
        return $total;
    } 

    public static function getMemberRatio($today_time, $pay)
    {
       $income = self::OpenMemberMoney($today_time);
       if (!$income) {
            return '0 %';
       }
       $ratio = sprintf("%.3f", $pay/$income);
       return ($ratio * 100). ' %';
    }
}
