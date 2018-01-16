<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Member;
use common\models\Relationship;
use common\models\Parameter;
use yii\web\Controller;
use backend\models\LocomotionUser;
/**
* 网络关系图
*/
class TreeController extends LdBaseController
{

    // 获取级别名称加相对应的图标的数组
    public function getUlevelImgStrArr()
    {
        $arr = Parameter::getUlevel();
        $reArr = [];
        foreach ($arr as $key => $value) {
            $reArr[$key]['img'] = Url::to('@web/statics/images/tree/tree'.$key.'.png');
            $reArr[$key]['str'] = $value;
        }
        return $reArr;
    }

    public function actionTree()
    {
        // 这里封装一层，双规改三规之类的，在这里改调用就行了
        $loginId = 1;
        $memberId = $loginId;

        // 查找会员统一处理
        if (Yii::$app->request->get('username')) {
            $getUsername = Yii::$app->request->get('username');
            $findMember = Member::find()->where('username = :username', [':username' => $getUsername])
            ->select('id')->one();
            if (!$findMember) {
                Yii::$app->session->setFlash('error', '未找到此用户');
            }
            else if ($findMember->id == $loginId) {
                Yii::$app->session->setFlash('error', '已经是最顶级了');
            }
            else {
                $relationship = Relationship::findOne(['member_id' => $findMember->id]);
                $pPathArr = explode(",", $relationship->p_path);
                if (!in_array($loginId, $pPathArr)) {
                    Yii::$app->session->setFlash('error', '此会员不在您当前团队中');
                } else {
                    $memberId = $findMember->id;
                }
            }
        }
        $lev = Yii::$app->request->get('lev') ? Yii::$app->request->get('lev') : 3; //默认显示的层数

        return $this->tree2($memberId, $lev);
        //return $this->tree3($memberId, $lev);
    }

    // 三轨
    public function tree3($memberId, $lev)
    {
        $UserModel = new LocomotionUser;
        if ($UserModel->load(yii::$app->request->post()) && $UserModel->validate()) {
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                    $UserModel->Move();
                    $transaction->commit();
                    return $this->redirect(['tree']);
            } catch (\Exception $e) {
                $transaction->rollback();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        $relationship = Relationship::find()->where(['member_id' => $memberId])->joinWith('member')->one();
        $models = $this->getUserData([$relationship], $lev, 3);
        $father_id = $relationship->father_id;
        $father = Member::find()->where(['id' => $father_id])->select('username')->one();
        $fatherUsername = $father ? $father->username : '';
        // var_dump($models[0]->member);exit;
        return $this->render('tree3', [
            'models' => $models,
            'UserModel' =>$UserModel,
            'ulevel' => $this->getUlevelImgStrArr(),
            'lev' => $lev,
            'fatherUsername' => $fatherUsername
        ]);
    }

    // 双轨
    private function tree2($memberId, $lev)
    {
        // 多维数组，显示由模板文件来控制，格式为[第一层数据，第二层数据，第三层数据]
        $relationship = Relationship::find()->where(['member_id' => $memberId])->joinWith('member')->one();
        // var_dump([$relationship]);exit;
        $models = $this->getUserData([$relationship], $lev, 2);
        $father_id = $relationship->father_id;
        $father = Member::find()->where(['id' => $father_id])->select('username')->one();
        $fatherUsername = $father ? $father->username : '';
        // var_dump($models[0]->member);exit;
        return $this->render('tree2', [
            'models' => $models,
            'ulevel' => $this->getUlevelImgStrArr(),
            'lev' => $lev,
            'fatherUsername' => $fatherUsername
        ]);
    }

    private function getUserData($arr, $lev, $num, $allIndex = 1)
    {
        $tempArr = [];
        $index = 0;
        $forArr = count($arr) == 1 ? $arr : $arr[count($arr) - 1];
        foreach ($forArr as $key => $value) {
            for ($i = 0; $i < $num; $i++) {
                if (isset($value->member_id)) {
                    $map = [];
                    $map['father_id'] = $value->member_id;
                    $map['area'] = $i;
                    $isUser = Relationship::find()->where($map)->joinWith('member')->one();
                    if (!$isUser) {
                        $isUser = NULL;
                        if ($value->member->activate > 0) {
                            $isUser = $value->member_id;
                        }
                    }
                    $tempArr[$index] = $isUser;
                } else {
                    $tempArr[$index] = NULL;
                }
                ++$index;
            }
        }
        $arr[$allIndex] = $tempArr;
        if (count($arr) < $lev) {
            return $this->getUserData($arr, $lev, $num, ++$allIndex);
        };
        return $arr;
        // return ArrayHelper::toArray($arr);
    }

    public function actionTreeAjax()
    {
        $UserModel= new LocomotionUser;
        $loginMember = Member::findOne(1);
        $user = $loginMember;
        $topMember = $loginMember;
        $tableStr = '';
        if (Yii::$app->request->isPost && Yii::$app->request->post('username')) {
            $username = Yii::$app->request->post('username');
            $findUser = Member::find()->where('username=:username',[':username'=>$username])->joinWith('relationship')->one();
            if ($findUser) {
                $topMember = $findUser;
            }
            else {
                Yii::$app->session->setFlash('error', "会员不存在或不在您的团队内");
            }
        }
        if($move=yii::$app->request->post('LocomotionUser')){
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $UserModel->move_user = $move['move_user'];
            $UserModel->to_user = $move['to_user'];
            if($UserModel->validate()){
                try{
                    $UserModel->reMove();
                    $transaction->commit();
                    return $this->redirect(['tree-ajax']);

                }catch (\Exception $e) {
                    $transaction->rollback();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }
        }

        $users = Member::find()->joinWith('relationship')->where('re_id=:rid',[':rid'=>$topMember->id])->orderBy(['id' => SORT_ASC])->all();
        $teamAmount = Member::find()->joinWith('relationship')->where(['like','re_path', ','.$topMember->id.','])->andWhere(['>','activate', 0])->count();
        return $this->render('tree-ajax', [
                                            'topMember'=>$topMember,
                                            'users'=>$users,
                                            'teamAmount'=>$teamAmount,
                                            'UserModel' =>$UserModel,
                                            ]);
    }

    public function actionGetAjaxSon(){
        $this->layout = false;
        $loginMember = Yii::$app->user->identity;
        $loginUser = Yii::$app->user->identity;

        $reid = Yii::$app->request->get('reid');
        $nn = Yii::$app->request->get('nn');
        $ppath = Yii::$app->request->get('pp');
        $pp = explode(",", $ppath);
        $users = Member::find()->joinWith('relationship')->where("re_id=:rid",[':rid'=>$reid])->orderBy(['id'=>SORT_ASC])->all();
        return $this->render('get-ajax-son',['users'=>$users, 'nn'=>($nn+1), 'pp'=>$pp, 'ppath'=>$ppath]);
    }


    public function actionTreeAjaxF()
    {
        $loginMember = Yii::$app->user->identity;
        $user = $loginMember;
        $topMember = $loginMember;
        $tableStr = '';
        if (Yii::$app->request->isPost && Yii::$app->request->post('username')) {
            $username = Yii::$app->request->post('username');
            $findUser = Member::find()->where('username=:username',[':username'=>$username])->andWhere(['like','p_path', ','.$loginMember->id.','])->one();
            if ($findUser) {
                $topMember = $findUser;
            }
            else {
                Yii::$app->session->setFlash('error', "会员不存在或不在您的团队内");
            }
        }
        $users = Member::find()->where('father_id=:rid',[':rid'=>$topMember->id])->orderBy(['id' => SORT_ASC])->all();
        $teamAmount = Member::find()->where(['like','p_path', ','.$topMember->id.','])->andWhere(['>','activate', 0])->count();
        return $this->render('tree-ajax-f', ['topMember'=>$topMember,'users'=>$users,'teamAmount'=>$teamAmount]);
    }

    public function actionGetAjaxSonF(){
        $this->layout = false;
        $loginMember = Yii::$app->user->identity;
        $loginUser = Yii::$app->user->identity;

        $reid = Yii::$app->request->get('reid');
        $nn = Yii::$app->request->get('nn');
        $ppath = Yii::$app->request->get('pp');
        $pp = explode(",", $ppath);
        $users = Member::find()->where("father_id=:rid",[':rid'=>$reid])->orderBy(['id'=>SORT_ASC])->all();
        return $this->render('get-ajax-son-f',['users'=>$users, 'nn'=>($nn+1), 'pp'=>$pp, 'ppath'=>$ppath]);
    }
}