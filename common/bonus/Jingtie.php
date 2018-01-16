<?php 
namespace common\bonus;

use Yii;
use yii\base\Model;
use common\models\RelationShip;
use common\models\BonusCalc;
use common\models\Parameter;
use common\models\Member;

/**
2、级别津贴：
 级别        总业绩     小区业绩     团队新增业绩提成       收入封顶
 经    理    50万        30%            1%               2万
中级经理    100万       30%            1.5%             5万
 高级经理    300万       30%            2%               无
   总监      500万       30%            2.5%             无
   董事      1000万      30%         2%（全国业绩加权平分）   无
*/
class Jingtie extends Model
{
	public $openMember;
	function __construct($openMember=null)
	{
		if ($openMember) {
			$this->openMember = $openMember;
		}
	}

	public function run()
	{
		if (!$this->openMember) {
			throw new \Exception("级别津贴参数不正确", 1);
		}
		$this->checkGetLevel();
		$this->emptyFengDay();
		$this->tuandui();
		$this->jiaquan();
		return true;
	}


	public function jiaquan()
	{
		$priiArr = Parameter::getValArrById(20);
		foreach ($priiArr as $key => $prii) {
			if ($prii <= 0) {
				continue;
			}
			$g_level = $key + 1;
			$query = Member::find();
			$query->where(['g_level'=>$g_level]);
			$list = $query->asArray()->all();
			if (!$list) {
				continue;
			}
			$num = count($list);
			$amount = $this->openMember->cpzj * $prii / 100;
			$everyAmount = $amount / $num;
			if ($everyAmount < 0.01) {
				continue;
			}
			foreach ($list as $key => $member) {
				BonusCalc::addBonusData($member['id'], '来自会员'.$this->openMember->username, 3, $everyAmount);
			}
		}
	}

	public function tuandui()
	{
		$priiArr = Parameter::getValArrById(17); //百分比
		$allFeng = Parameter::getValArrById(18);
		$dayFeng = Parameter::getValArrById(19);
		$ids = explode(',', $this->openMember->relationship->p_path);
		$list = RelationShip::find()->where(['in','member_id', $ids])->orderBy(['re_level'=>SORT_DESC])->all();
		foreach ($list as $key => $re) {
			$g_level = $re->member->g_level;
			if ($g_level == 0) {
				continue;
			}
			$index = $g_level - 1;
			$cpzj = $this->openMember->cpzj;
			$amount = $cpzj * $priiArr[$index] / 100;
			// 总封顶
			$feng = $allFeng[$index];
			if (is_numeric($feng)) {
				if ($re->feng + $amount > $feng) {
					$amount = $feng - $re->feng;
				}
			}
			// 日封顶
			$feng = $dayFeng[$index];
			if (is_numeric($feng) && $amount > 0) {
				if ($re->feng_day + $amount > $feng) {
					$amount = $feng - $re->feng_day;
				}
			}
			if ($amount > 0) {
				RelationShip::updateAllCounters(['feng'=>$amount, 'feng_day'=>$amount], ['member_id'=>$re->member_id]);
				BonusCalc::addBonusData($re->member_id, '来自会员'.$this->openMember->username, 2, $amount);
			}
		}
	}

	public function checkGetLevel()
	{
		$yejiArr = Parameter::getValArrById(15); // 单位:单数 用|分割
		$minYejiArr = Parameter::getValArrById(16); // 小区单数 用|分割
		$query = RelationShip::find();
		$ids = explode(',', $this->openMember->relationship->p_path);
		$query->where(['in','member_id',$ids]);
		$query->orderBy(['re_level'=>SORT_DESC]);
		foreach ($yejiArr as $key => $yeji) {
			$min = $minYejiArr[$key];
			$max = $yeji - $min;
			$tempQuery = $query;
			$tempQuery->andWhere(['or',
				['and',['>=', 'l_money', $min],['>=', 'r_money', $max]],
				['and',['>=', 'r_money', $min],['>=', 'l_money', $max]]
			]);
			// $tempQuery->andWhere(['g_level'=>$key]);
			$list = $tempQuery->all();
			$g_level = $key + 1;
			foreach ($list as $k => $re) {
				Member::updateAll(['g_level'=>$g_level], ['id'=>$re->member_id, 'g_level'=>$key]);
			}
		}
	}

	public function emptyFengDay()
	{
		$dayTime = strtotime(date('Y-m-d'));
		return RelationShip::updateAll(['feng_day'=>0, 'feng_day_time'=>$dayTime], ['<', 'feng_day_time',$dayTime]);
	}
}