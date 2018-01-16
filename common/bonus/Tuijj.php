<?php 
namespace common\bonus;

use Yii;
use yii\base\Model;
use common\models\RelationShip;
use common\models\BonusCalc;

/**
*  推荐奖
*/
class Tuijj extends Model
{
	public $openMember;
	public $proportionArr; // 比例数组，与会员级别对应
	function __construct($openMember=null, $proportionArr=null)
	{
		if ($openMember) {
			$this->openMember = $openMember;
		}

		if ($proportionArr) {
			$this->proportionArr = $proportionArr;
		}
	}

	// 比例2|2|4 第一代|第二代...
	public function run()
	{
		if (!$this->openMember || !$this->proportionArr) {
			throw new \Exception("分享收益参数不正确", 1);
		}
		$limit = count($this->proportionArr);
		$ids = explode(',', $this->openMember->relationship->re_path);
		$list = RelationShip::find()->where(['in','member_id', $ids])->limit($limit)->orderBy(['re_level'=>SORT_DESC])->all();
		foreach ($list as $key => $re) {
			$prii = $this->proportionArr[$key];
			$amount = $this->openMember->cpzj * $prii / 100;
			if ($amount > 0) {
				BonusCalc::addBonusData($re->member_id, '来自会员'.$this->openMember->username, 1, $amount);
			}
		}
		return true;
	}

	// 比例2|2|4与级别对应
	// public function run()
	// {
		
	// 	if (!$this->openMember || !$this->proportionArr) {
	// 		throw new \Exception("推荐奖参数不正确", 1);
	// 	}

	// 	$re = RelationShip::find()->where(['member_id'=>$this->openMember->relationship->re_id])->one();
	// 	$index = $re->member->u_level;
		
	// 	if (!$pro = $this->proportionArr[$index]) {
	// 		throw new \Exception("推荐奖参数不正确", 1);
	// 	}
	// 	$amount = $this->openMember->cpzj * $pro / 100;
	// 	if ($amount > 0) {
 //            if ($re->member->is_lock == 0) {
 //            	BonusCalc::addBonusData($re->member_id, '来自会员'.$this->openMember->username, 1, $amount);
 //            }
	// 	}
	// 	return true;
	// }
}