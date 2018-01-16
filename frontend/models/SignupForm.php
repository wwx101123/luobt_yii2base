<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Member;
use common\models\BankCard;
use common\models\Account;
use common\models\Relationship;
use common\models\MemberInfo;
use common\models\Parameter;
use common\models\Address;
use common\models\Product;
use common\models\Order;
use common\models\OrderGoods;
/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_hash_confirm;
    public $password_two;
    public $password_hash_two_confirm;

    public $shop_name;
    public $u_level;
    private $_dan;
    private $_cpzj;
    // 关系模块
    public $father_name;
    public $re_name;
    public $area;
    private $_re_id;
    private $_father_id;
    private $_re_path;
    private $_p_path;
    private $_shop_id;
    private $_re_level;
    private $_p_level;
    // 银行信息
    public $name;
    public $code;
    public $phone;
    // 注册协议
    public $agreement = false;
    // 收货地址
    public $address_name;
    public $address;
    public $address_tel;
    //注册选择商品
    public $goods_id;
    // public static $arr = ['左区', '中区', '右区'];
    public static $arr = ['左区', '右区'];

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['def'] = ['username','password','shop_name', 'u_level', 'father_name', 'area', 're_name', 'name', 'code', 'phone', 'password_hash_confirm','address_name','address','address_tel','goods_id','password_two','password_hash_two_confirm']; //要验证的写到这里面
        return $scenarios;
    }

    public function attributeLabels()
    {
        $member = new Member();
        $labels = [
            'shop_name' => '服务中心',
            'u_level' => '申请级别',
            'father_name' => '接点人',
            'area' => '所在区域',
            're_name' => '推荐人',
            'name' => '姓名',
            'code' => '身份证号',
            'phone' => '联系电话',
            'agreement' => '我已阅读并同意',
            'password_hash_confirm' => '确认密码',
            'password_two' => '二级密码',
            'password_hash_two_confirm' => '确认二级密码',
            'address'=>'收货地址',
            'address_name'=>'收货人',
            'address_tel'=>'收货人电话',
            'goods_id'=>'注册产品',
        ];
        return array_merge($member->AttributeLabels(), $labels);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\Member', 'message' => '该用户名已使用'],
            ['username', 'string', 'min' => 6, 'max' => 32],
            [['username'],'match','pattern'=>'/^[a-z0-9]+$/','message'=>'用户名只能由字母数字组成'],

            // ['shop_name', 'string', 'min' => 6, 'max' => 32],
            ['shop_name', 'required'],
            ['shop_name', 'checkShopname'],

            ['re_name', 'required'],
            ['re_name', 'checkRename'],

            // ['shop_name', 'string', 'min' => 6, 'max' => 32],
            ['father_name', 'required'],
            ['father_name', 'checkFathername'],

            ['area', 'required'],
            ['area', 'in',  'range' => [0, 1], 'message' => '请选择正确的所在区域'],

            ['u_level', 'required'],
            // ['u_level', 'in',  'range' => [0, 1, 2], 'message' => '请选择正确的申请级别'],
            ['u_level', 'checkUlevel'],

            ['email', 'trim'],
            // ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\Member', 'message' => 'email地址已被使用'],

            [['password','password_two'],'required'],
            [['password','password_two'], 'string', 'min' => 6],

            [['password_hash_confirm','password_hash_two_confirm' ],'required'],
            ['password_hash_confirm','compare','compareAttribute'=>'password'],
            ['password_hash_two_confirm','compare','compareAttribute'=>'password_two'],

            // ['agreement', 'boolean'],
            // ['agreement', 'checkAgreement'],
            ['goods_id','required'],
            ['goods_id', 'checkGoodsMoney'],
            [['address_name','address_tel','address'],'string'],
            [['address_name','address_tel','address'],'required'],
            [['name','code','phone'],'string','max'=>64],




        ];
    }
    public function checkGoodsMoney($attribute,$params){
        $ids = $this->goods_id;
        //var_dump($ids);exit;
        $product_list = Product::find()->select('id,present_price')->where(['in','id',$ids])->sum('present_price');
        $cpzj = Parameter::getCpzjByLevel($this->u_level); // cpzj - 注册金额
        if($product_list!=$cpzj){
            return $this->addError('goods_id','注册产品的总价格应该等于您的注册金额');
        }
    }
    public function checkAgreement($attribute, $params)
    {
        if (!$this->agreement) {
            return $this->addError($attribute, '请先阅读并同意《注册协议》');
        }
    }

    public function checkUlevel($attribute, $params)
    {
        $uLevelArr = Parameter::getUlevel();
        if (!isset($uLevelArr[$this->u_level])) {
            return $this->addError($attribute, '请选择正确的注册级别');
        }
        $danArr = Parameter::getDan();
        $this->_dan = $danArr[$this->u_level];
        $this->_cpzj = Parameter::getCpzjByLevel($this->u_level);
    }

    public function checkShopname($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $shop_name = $this->shop_name;
            $member = Member::find()->where('username=:username',[':username'=>$shop_name])->select('id,is_agent')->one();
            if (!$member || $member->is_agent == 0) {
                $this->addError($attribute, '此报单中心不存在');
            }
            else {
                $this->_shop_id = $member->id;
            }
        }
    }

    public function checkFathername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $father = Member::find()->where('username=:username', [':username'=>$this->father_name])->select('id,activate')->one();
            if (!$father) {
                return $this->addError($attribute, '接点人不存在');
            }
            if ($father->activate == 0) {
                return $this->addError($attribute, '接点人未激活');
            }
            // 左右区一起在这里验证
            $have = Relationship::find()->where(['father_id'=>$father->id, 'area'=>$this->area])->one();
            if ($have) {
                return $this->addError('area', '此位置已被注册');
            }
            $fatherRelation = Relationship::findOne(['member_id'=>$father->id]);
            $this->_father_id = $fatherRelation->member_id;
            $this->_p_path = $fatherRelation->p_path . $fatherRelation->member_id . ',';
            $this->_p_level = $fatherRelation->p_level + 1;
        }
    }

    public function checkRename($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $re = Member::find()->where('username=:username', [':username'=>$this->re_name])->select('id,activate')->one();
            if (!$re) {
                return $this->addError($attribute, '推荐人不存在');
            }
            if ($re->activate == 0) {
                return $this->addError($attribute, '推荐人未激活');
            }
            $reRelation = Relationship::findOne(['member_id'=>$re->id]);
            $this->_re_id = $reRelation->member_id;
            $this->_re_level = $reRelation->re_level + 1;
            $this->_re_path = $reRelation->re_path . $reRelation->member_id . ',';
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        // $this->validate();
        // var_dump($this->getErrors());
        // exit("xx");
        if (!$this->validate()) {
            return null;
        }
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $user = new Member();
            $user->username = $this->username;
            $user->shop_id = $this->_shop_id;
            $user->u_level = $this->u_level;
            $user->dan = $this->_dan;
            $user->cpzj = $this->_cpzj;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->setPasswordTwo($this->password_two);

            if (!$user->save()) {
                throw new \Exception("会员资料插入失败", 1);
            }
            // 创建账户钱包
            $account = new Account();
            $account->member_id = $user->id;
            if ( !$account->save() )
            {
                throw new \Exception("钱包创建失败", 1);
                
            }
            // 添加关系
            $relationship = new Relationship;
            $relationship->member_id = $user->id;
            $relationship->father_id = $this->_father_id;
            $relationship->area = $this->area;
            $relationship->re_id = $this->_re_id;
            $relationship->p_path = $this->_p_path;
            $relationship->re_path = $this->_re_path;
            $relationship->re_level = $this->_re_level;
            $relationship->p_level = $this->_p_level;
            if (!$relationship->save()) {
                throw new \Exception("添加关系失败", 1);
            }
            // 添加会员信息
            $memberInfo = new MemberInfo;
            $memberInfo->member_id = $user->id;
            $memberInfo->name = $this->name;
            $memberInfo->code = $this->code;
            $memberInfo->phone = $this->phone;
            if (!$memberInfo->save()) {
                throw new \Exception("会员信息数据有误", 1);
            }

            //添加收货地址
            $ress = new Address;
            $ress->user_id = $user->id;
            $ress->name = $this->address_name;
            $ress->tel = $this->address_tel;
            $ress->address = $this->address;
            if (!$ress->save()) {
                throw new \Exception("收货地址填写有误", 1);
            }
            //添加到订单
            $this->addOrder($user->id);

            $transaction->commit();
            return $user;
        } catch (\Exception $e) {
            $transaction->rollback();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return null;
        }
        
    }
    public function addOrder($user_id){
        $goods = Product::find()->where(['in','id',$this->goods_id])->all();
          
        $order= new Order;
        $order->order_no = $order->getGoodsNo();
        $order->user_id = $user_id;
        $order->order_status = 1;
        $order->name = $this->address_name;
        $order->address = $this->address;
        $order->tel = $this->address_tel;
        $cpzj= Parameter::getCpzjByLevel($this->u_level);
        $order->goods_amount = $cpzj;
        $order->order_amount = $cpzj;
        $order->create_time =time();
        $order->confirm_time =time();
        if ($order->save()) {
            foreach ($goods as $key => $value) {
                $order_goods = new OrderGoods;
                $order_goods->order_id = $order->id;
                $order_goods->goods_id = $value['id'];
                $order_goods->goods_name = $value['goods_name'];
                $order_goods->buy_number = 1;
                $order_goods->market_price = $value['market_price'];
                $order_goods->present_price = $value['present_price'];
                $order_goods->goods_img = $value['goods_img'];
                $order_goods->create_time = time();
                if (!$order_goods->save()) {
                    throw new \Exception("订单保存失败".var_dump($order_goods->errors));
                }
            }
        }
        else{
            throw new \Exception("订单保存失败".var_dump($order->errors));
        };
    }


}
