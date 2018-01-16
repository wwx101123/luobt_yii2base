<?php
namespace console\controllers;

use Yii;
use yii\console\controller;
use common\models\BonusCalc;

/**
* 结算分红
*/
class BonusController extends controller
{
	public function actionFenhong()
	{
		// BonusCalc::addFenhong(1, 10000);
		BonusCalc::fenhong();
		BonusCalc::calcBonusList();
	}
}
?>