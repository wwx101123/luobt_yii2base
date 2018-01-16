<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use common\bonus\Tuijj;
use common\bonus\Jingtie;
use common\bonus\Baodan;

/**
* 奖金计算
*/
class BonusCalc extends Model
{

	public static function calc($member, $tp=0)
	{
        // 添加业绩
        Parameter::updateAllCounters(['val'=>$member->cpzj], ['id'=>26]);
        // 推荐奖
        $proArr = Parameter::getValArrById(25);
        $tuijj = new Tuijj($member, $proArr);
        if(!$tuijj->run()){
            throw new \Exception("推荐奖计算失败", 1);
        }

        $jingtie = new Jingtie($member);
        if(!$jingtie->run()){
            throw new \Exception("津贴计算失败", 1);
        }

        if ($tp == 0) {
            $baodan = new Baodan($member);
            if(!$baodan->run()){
                throw new \Exception("报单奖计算失败", 1);
            }
        }
		self::calcBonusList();
	}

    public static function baodanfei($shopid, $uLevel,$fromUser)
    {
        $amountArr = Parameter::getValArrById(15);
        $amount = isset($amountArr[$uLevel]) ? $amountArr[$uLevel] : 0;
        if ($amount > 0) {
            self::addBonusData($shopid, "开通会员{$fromUser}", 8, $amount);
        }
    }

    public static function checkGetLevel($memberId)
    {
        $mustF4 = Parameter::getValArrById(13);
        $mustF4Arr = [];
        foreach ($mustF4 as $key => $str) {
            $arr = explode('-', $str); // 从大到小排
            rsort($arr);
            $mustF4Arr[] = $arr;
        }
        
        $re = Relationship::find()->andWhere(['member_id'=>$memberId])->one();
        $query = Relationship::find();
        $p_path = explode(',', $re->p_path);
        $query->andWhere(['in', 'member_id', $p_path]);
        $list = $query->all();
        foreach ($list as $k => $re) {
            $yj = [$re->l, $re->r, $re->lr];
            rsort($yj);
            $lv = -1;
            foreach ($mustF4Arr as $index => $arr) {
                foreach ($arr as $key => $v) {
                    if ($v > $yj[$key]) {
                        continue 2;
                    }
                }
                $lv = $index;
            }
            if ($lv < 0) {
                continue;
            }
            $lv += 1;
            // 升级
            Member::updateAll(['g_level'=>$lv], ['id'=>$re->member_id]);

        }

    }

	public static function addFenhong($memberId, $amount, $qiShu=1)
	{
        $qi = Parameter::getValById(5);
        $model = new Fenhong;
        $model->amount = $qi;
        $model->money = $amount;
        $model->rdt = time();
        $model->uid = $memberId;
        $model->f_amount = 0;
        $model->f_money = 0;
        $model->qi = $qiShu;
        $model->dft = strtotime(date('Y-m-d')) - 1;
        if (!$model->save()) {
            throw new \Exception("添加分红点失败", 1);
        }
	}

    public static function futou($user)
    {
        $moneyArr = Parameter::getValArrById(8);
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $last = Fenhong::find()->andWhere(['uid'=>$user->id])->orderBy(['qi'=>SORT_DESC])->one();
            if (!$last || $last->amount != $last->f_amount || $last->qi > count($moneyArr)) {
                throw new \Exception("当前不可复投", 1);
            }
            $futouMoney = $moneyArr[$last->qi - 1];
            $jiangjin = $futouMoney / 2;
            $baodan = $futouMoney - $jiangjin;

            $kou1 = Account::updateAllCounters(['account3'=>-$jiangjin, 'account5'=>-$baodan], ['and', ['member_id'=>$user->id], ['>=', 'account3', $jiangjin], ['>=', 'account5', $baodan]]);
            if (!$kou1) {
                throw new \Exception("扣除积分失败", 1);
            }
            self::addFenhong($user->id, $futouMoney, $last->qi + 1);
            $transaction->commit();/*提交事物*/
            return NULL;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $e->getMessage();
        }

    }

    public static function fenhong($uid=NULL,$isGetInfo=false,$offset=0,$limit=0)
    {
        $bei = Parameter::getValById(4);
        $priiArr = Parameter::getValArrById(6); //account3,account5,account6
        $now = strtotime(date('Y-m-d'));
        // $now = time(); // 测试
        $query = Fenhong::find();
        $query->andWhere('amount>f_amount');
        $query->andFilterWhere(['uid'=>$uid]);
        $query->orderBy(['id'=>SORT_ASC]);
        $query->andWhere(['<', 'dft',$now]);
        if ($isGetInfo) {
            return $query->count();
        }
        if ($limit > 0) {
            $query->offset($offset)->limit($limit);
        }
        $list = $query->all();
        foreach ($list as $key => $model) {
            $model->dft = $now;
            if (!$model->save()) { // 时间没设置成功就不往下执行了
                continue;
            }
            $money = $model->money;
            $all_money = $money * $bei;
            $everyMoney = $all_money / $model->amount;
            if ($everyMoney <= 0) {
                continue;
            }
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                // 进奖金
                $amount = 0.94 * $everyMoney * $priiArr[0] / 100;
                if ($amount > 0) {
                    self::addBonusData($model->uid, "来自第{$model->qi}期", 1, $amount, 8);
                    // self::addBonusData($model->uid, "来自第{$model->qi}期", 2, $amount/2, 8); // 给一半给复投积分
                    self::tuijj($model->uid, $amount);
                    self::jicha($model->uid, $amount);
                }
                $money = 10000; // 这是一个奇葩的要求
                $all_money = $money * $bei;
                $everyMoney = $all_money / $model->amount;
                // 进股权
                $amount = $everyMoney * $priiArr[1] / 100;
                if ($amount > 0) {
                    self::addBonusData($model->uid, "来自第{$model->qi}期", 3, $amount, 5);
                }
                // 进基金
                $amount = $everyMoney * $priiArr[2] / 100;
                if ($amount > 0) {
                    self::addBonusData($model->uid, "来自第{$model->qi}期", 4, $amount, 6);
                }

                $model->f_amount += 1;
                $model->f_money += $everyMoney;
                if (!$model->save()) {
                    throw new \Exception("保存记录不成功", 1);
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollback();
                echo $e->getMessage();
                return $e->getMessage();
            }
        }
        return $list ? count($list) : 0;
    }

    public static function tuijj($fromUid, $amount)
    {
        $priiArr = Parameter::getValArrById(10);
        $limit = count($priiArr);
        $re = Relationship::find()->where(['member_id'=>$fromUid])->one();
        $repath = explode(',', $re->re_path);
        $list = Relationship::find()->where(['in', 'member_id', $repath])->orderBy(['re_level'=>SORT_DESC])->limit($limit)->all();
        foreach ($list as $key => $m) {
            $prii = $priiArr[$key] / 100;
            $money = $amount * $prii;
            if ($money > 0) {
                self::addBonusData($m->member_id, '来自会员'.$re->member->username, 5, $money);
            }
        }

    }

    public static function jicha($fromUid, $amount)
    {
        $priiArr = Parameter::getValArrById(11);
        $limit = count($priiArr);
        $re = Relationship::find()->where(['member_id'=>$fromUid])->one();
        // $repath = explode(',', $re->re_path);
        $ppath = $re->p_path;
        if (!$ppath) {
            return;
        }
        $ppath = explode(",", $ppath);
        $list = Relationship::find()->joinWith('member')->andWhere(['in', '{{%relationship}}.member_id', $ppath])->andWhere(['>', '{{%member}}.g_level', 0])->orderBy(['p_level'=>SORT_DESC])->all();
        $gLevel = 0;
        $maxGlevel = count($priiArr);
        
        // echo json_encode($repath);
        // echo $re->re_path;
        $fen = 0;
        foreach ($list as $key => $m) {
            $gl = $m->member->g_level;
            if ($gLevel >= $gl) {
                continue;
            }
            $index = $gl - 1;
            $prii = $priiArr[$index] / 100;
            $prii -= $fen; // 极差，减去已分
            $fen += $prii; // 记录到已分里面
            $money = $amount * $prii;
            if ($money > 0) {
                self::addBonusData($m->member_id, '来自会员'.$re->member->username, 6, $money);
                //这里还有个平级奖
                self::pingji($m, $money, $gl);
            }
            $gLevel = $gl;
            if ($gLevel >= $maxGlevel) {
                return;
            }
        }
    }

    public static function pingji($re, $amount, $gl)
    {
        // 平级奖 给上N个同级别的人的奖
        $priiArr = Parameter::getValArrById(12);
        $limit = count($priiArr);
        $repath = $re->re_path;
        if (!$repath) {
            return;
        }
        $repath = explode(',', $repath);
        $list = Relationship::find()->joinWith('member')->andWhere(['in', '{{%relationship}}.member_id', $repath])->andWhere(['{{%member}}.g_level'=>$gl])->orderBy(['re_level'=>SORT_DESC])->limit($limit)->all();
        foreach ($list as $key => $m) {
            $prii = $priiArr[$key] / 100;
            $money = $amount * $prii;
            if ($money > 0) {
                self::addBonusData($m->member_id, '来自会员'.$re->member->username, 7, $money);
            }
        }
    }

	public static function addBonusData($member_id, $bz='', $type, $amount, $account_type=3)
	{
        
        $model = new Bonus;
        $model->member_id = $member_id;
        $model->reg_id = $member_id;
        $model->amount = $amount;
        $model->bonus_type = $type;
        $model->state = 0;
        $model->account_type = $account_type;
        $model->bz = $bz;
        $model->clear_time = 0;
        $model->create_time = time();
        $model->today_time = strtotime(date('Y-m-d'));
        if (!$model->save()) {
            throw new \Exception(json_encode($model->errors));
            throw new \Exception('保存失败');
        }
        // 有一部分进消费积分
        $prii = Parameter::getShui($type);
        $account5 = $amount * $prii;
        if ($account5 > 0) {
            $model = new Bonus;
            $model->member_id = $member_id;
            $model->reg_id = $member_id;
            $model->amount = -$account5;
            $model->bonus_type = 6;
            $model->state = 1;
            $model->account_type = $account_type;
            $model->bz = $bz;
            $model->clear_time = time();
            $model->create_time = time();
            $model->today_time = strtotime(date('Y-m-d'));
            if (!$model->save()) {
                throw new \Exception(json_encode($model->errors));
                throw new \Exception('保存失败');
            }
        }
	}

	// 这里搞个类单线程的来算奖前金额和奖后金额
    public static function calcBonusList()
    {   

        set_time_limit(0);
        ignore_user_abort(true);
        ini_set('memory_limit','1024M');

        $filePath = './autoPaidui.lock';
        if(!file_exists($filePath))
        {
            $fp = fopen($filePath,'w');
            fclose($fp);
        }
        $fp = fopen($filePath,'r');
        if(!flock($fp,LOCK_EX | LOCK_NB)) // 用于确保打开的唯一性
        {
            return;
        }
        do {
            $models = Bonus::find()->where(['state'=>0])->limit(100)->orderBy(['id'=>SORT_ASC])->all();
            foreach ($models as $key => $model) {

                // $agent_name = 'account'.$model->account_type;/*对应奖金字段*/

                // $amount = $model->amount;
                // $acount = Account::find()->where(['member_id'=>$model->member_id])->select([$agent_name])->one();
                Account::addBonus($model->member_id, $model->amount, $model->bonus_type, $model->bz);
                // Account::updateAllCounters([$agent_name=>$amount],['member_id'=>$model->member_id]);
                // if ($agent_name == 'account3' || $agent_name == 'account8') {
                //     Account::updateAllCounters(['account2'=>$amount],['member_id'=>$model->member_id]);
                // }
                // if ($model->bonus_type == 10) {
                //     Account::updateAllCounters(['account7'=>-$amount],['member_id'=>$model->member_id]);
                // }

                $model->state = 1;
                $model->clear_time = time();
                // $model->start_amount = $acount[$agent_name];
                // $model->end_amount = $acount[$agent_name] + $amount;
                $model->start_amount = 0;
                $model->end_amount = 0;
                $model->update();

            }

        }while ($models);

        flock($fp,LOCK_UN);
    }
}