<?php 
namespace common\bonus;

use Yii;
use yii\base\Model;
use common\models\RelationShip;
use common\models\BonusCalc;
use common\models\Parameter;

/**
*  报单奖
*/
class Baodan extends Model
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
			throw new \Exception("报单费参数不正确", 1);
		}
		$prii = Parameter::getValById(6);
		$amount = $this->openMember->cpzj * $prii / 100;
		if($amount > 0) {
			BonusCalc::addBonusData($this->openMember->shop_id, '来自会员'.$this->openMember->username, 4, $amount);
		}
		return true;
	}

}