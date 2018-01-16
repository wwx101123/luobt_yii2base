<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use backend\models\CheckPassword;
use common\models\User;
use common\models\Member;
use common\models\Recharge;
use common\models\ToCash;
use common\models\Bonus;
/**
 * Site controller
 */
class SiteController extends LdBaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','check-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        //激活会员总数
        $user_number = Member::find()->where(['>', 'activate', 0])->asArray()->count();
        //充值总额
        $recharge_money = Recharge::find()->where(['state' => 1])->asArray()->sum('re_money');
        //提现金额，当state=1时显示提现金额，其他时小时为0
        $toCash_money = ToCash::find()->select('sum(case when state = 1 then to_money else to_money * 0 end) as to_money')->asArray()->one();

        $bonus = Bonus::find()->select('sum(case when bonus_type < 6 then amount else amount * 0 end) as all_bonus')->asArray()->one();
        $all_bonus = Bonus::find()->groupBy('today_time')->select('sum(amount) as all_bonus, today_time')->asArray()->all();

        $Array = [];
        foreach ($all_bonus as $key => $val) {
            $cpzj = Member::OpenMemberMoney($val['today_time']);
            $Array[$key]['today_time'] = date('Y-m-d', $val['today_time']);
            $Array[$key]['item1'] = $val['all_bonus']; //奖金总额
            $Array[$key]['item2'] = $cpzj; //开通会员的钱
        }

        return $this->render('index', [
            'user_number' => $user_number,
            'recharge_money' => $recharge_money,
            'toCash_money' => $toCash_money,
            'bonus' => $bonus,
            'Array' => $Array,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    public function actionCheckPassword($backUrl=''){

        $model = new CheckPassword;
        $user = User::findOne($this->user->id);
        if($model->load(yii::$app->request->post())){
            // var_dump($model->password);exit;
            if($user->validatePasswordTwo($model->password)){
                yii::$app->session['password-two']= 1;
                if(empty($backUrl)){
                    $backUrl='site/index';
                }
                return $this->redirect([$backUrl]);
            }
            Yii::$app->getSession()->setFlash('error', '二级密码不正确!');
        }
        return $this->render('check-password',['model'=>$model]);
    }
}
