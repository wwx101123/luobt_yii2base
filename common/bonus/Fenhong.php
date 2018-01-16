<?php 
namespace common\bonus;

use Yii;
use yii\base\Model;
use common\models\RelationShip;
use common\models\BonusCalc;
use common\models\Parameter;
use common\models\MemberIn;
use common\models\Member;

/**
*  分红

22	消费股东前	500|10000【修改】	排名 用|分割 大于最后一个参数为最后一个区域
23	消费股东封顶	0-3|0-2_3-3|0-2_3-3【修改】	用|分割，与上面区域对应推荐N人-N倍_N人-N倍
24	消费股东收益奖金分流	50|50【修改】	现金积分|消费积分

*/
class Fenhong extends Model
{
	public static function run($money=0)
	{
		if ($money <= 0) {
			return false;
		}
		$infoArr = self::getFenHongMember();
		$ids = $infoArr[0];
		$fArr = $infoArr[1];
		$sum = $infoArr[2];
		if ($sum <= 0) {
			return false;
		}
		$everyOne = $money / $sum;
		$list = Member::find()->where(['in', 'id', $ids])->select('id,dan')->asArray()->all();
		foreach ($list as $key => $member) {
			$f = $fArr[$member['id']];
			$amount = $everyOne * $member['dan'];
			if ($amount > $f) {
				$amount = $f;
			}
			if($amount > 0) {
				RelationShip::updateAllCounters(['feng1'=>$amount], ['member_id'=>$member['id']]);
				BonusCalc::addBonusData($member['id'], '', 5, $amount);
			}
		}
		return true;
	}

	// 分红的资料[id数组，距离封顶值，总单数]
	public static function getFenHongMember()
	{
		$list = MemberIn::find()->asArray()->all();
		// var_dump($list);exit;
		$ids = []; // 封顶值
		$fArr = []; // 封顶
		foreach ($list as $key => $in) {
			$f = self::checkFengding($in['member_id']);
			if ($f <= 0) {
				continue;
			}
			$ids[] = $in['member_id'];
			$fArr[$in['member_id']] = $f;
		}
		$sum = Member::find()->where(['in', 'id', $ids])->sum('dan');
		if (!$sum) {
			$sum = 0;
		}
		return [$ids, $fArr, $sum];
	}

	// 检测 返回 true 为未封顶
	public static function checkFengding($memberId)
	{
		$indexArr = Parameter::getValArrById(22);
		$fengArr = Parameter::getValArrById(23);
		$model = MemberIn::find()->where(['member_id' => $memberId])->one();
		$i = 0;
		foreach ($indexArr as $key => $index) {
			if ($model->id <= $index) {
				break;
			}
			$i++;
		}
		$feng = explode('_', $fengArr[$i]); // ['0-2','3-3']推荐N人-N倍
		// var_dump($feng);exit;
		$re = RelationShip::find()->where(['member_id' => $model->member_id])->one();
		$bei = 0;
		foreach ($feng as $key => $value) {
			$arr = explode('-', $value);
			if ($re->re_nums >= $arr[0]) {
				$bei = $arr[1];
			}
		}
		$f = $re->member->cpzj * $bei;
		if ($f > $re->feng1) {
			return $f - $re->feng1;
		}
		return false;
	}

}