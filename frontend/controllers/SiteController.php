<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\Product;
use common\models\Sort;
use common\models\Member;
use frontend\models\UpdatePassword;
use frontend\models\CheckPassword;

use common\models\Post;
use common\modelsMember;
use common\models\Parameter;


/**
 * Site controller
 */
class SiteController extends LdBaseController
{
    public $layout = 'main_home_banner_img';

    public function behaviors(){
        return  [ 
                'access' => [
                    'class' => AccessControl::className(),

                    'rules' => [
                        [
                            'actions' => ['login','captcha','request-password-reset','reset-password','admin-login','check-password'],
                            'allow' => true,
                            'roles' => ['?'],
                        ],
                        [
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

    public function actionAdminLogin()
    {
        $id = Yii::$app->request->get('uid');
        // exit($id);
        $tk = Yii::$app->request->get('token');
        if (empty($tk)) {
            exit('参数不正确');
        }
        $member = Member::find()->where('id = :id AND auto_login_token = :tk', [':id' => $id,  ':tk' => $tk])->one();
        if (!$member) {
            exit('参数不正确');
        }

        Yii::$app->user->logout();

        Yii::$app->session['admin-login'] = 1;
        $member->auto_login_token= '';
        $member->save();
        Yii::$app->user->login($member, 0);
        return $this->goHome();
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'maxLength' => 4,
                'minLength' => 4,
                'backColor'=>0xFFFFFF,
                'foreColor'=>0x202022,
                'offset'=>4, 
                'testLimit'=>4, 
                'height'=>45,
                'transparent'=>true
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $member = Member::find()->joinWith('account')->where(['{{%member}}.id'=>$this->user->id])->one();
        
        $news = Post::find()->where('is_show = 1')->asArray()->all();
        $is_agent = $member->is_agent ? '是' : '否';
        //echo $member->g_level;exit;
        $g_level = Parameter::getGlevelName($member->g_level);
        
        return $this->render('index', [
            'news' => $news,
            'member' => $member,
            'is_agent' => $is_agent,
            'g_level' => $g_level,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout = 'login';
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
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdatePassword()
    {
        $this->layout = 'main_inside_banner_img';
        $model = new UpdatePassword;
        $model->grade= 1;
        if($model->load(yii::$app->request->post()) && $model->validate()){
            Yii::$app->session->setFlash('info', '密码修改成功!');
            return $this->refresh();
        }

        return $this->render('update-password',['model'=>$model]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    public function actionCheckPassword($backUrl='site/index'){
        $this->layout = 'main_inside_banner_img';
        
        $model = new CheckPassword;
        $user = Member::findOne($this->user->id);
        if($model->load(yii::$app->request->post())){
            if($user->validatePasswordTwo($model->password)){
                yii::$app->session['password-two']= 1;
                return $this->redirect([$backUrl]);
            }
            $this->error('二级密码不正确');
        }
        return $this->render('check-password',['model'=>$model]);
    }
}
