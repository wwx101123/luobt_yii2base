<?php 
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;
use yii\helpers\Url;
use common\models\Member;
class Routing extends Model{
	public static $title=[
							[
								'name'=>'首页',
								'url'=>'site/index',
								'child'=>[],
							],
							[
								'name'=>'新闻公告',
								'url'=>'post/index',
								'child'=>[]
							],
							[
								'name'=>'财务中心',
								'url'=>'#',
								'child'=>[
											['child_name'=>'账户信息','child_url'=>'member/info',],
											['child_name'=>'提现申请','child_url'=>'to-cash/index'],
											['child_name'=>'积分转换','child_url'=>'account-change/index'],
											['child_name'=>'积分转账','child_url'=>'account-transfer/index'],
											['child_name'=>'充值','child_url'=>'recharge/index'],
										]
							],
							[
								'name'=>'佣金明细',
								'url'=>'#',
								'child'=>[
											['child_name'=>'奖金明细','child_url'=>'bonus/index'],
											['child_name'=>'账户流水','child_url'=>'account-history/index'],
										]
							],
							[
								'name'=>'客户管理',
								'url'=>'#',
								'child'=>[
											['child_name'=>'登记注册','child_url'=>'signup/index',],
											['child_name'=>'未开通会员','child_url'=>'agent/unactivate'],
											['child_name'=>'已开通会员','child_url'=>'agent/member'],
										]
							],
							[
								'name'=>'系普图',
								'url'=>'#',
								'child'=>[
											['child_name'=>'推荐关系图','child_url'=>'tree/tree-ajax',],
											['child_name'=>'接点关系图','child_url'=>'tree/tree'],
											//['child_name'=>'个人销售','child_url'=>'member/my-reg'],
										]
							],
							[
								'name'=>'修改资料',
								'url'=>'#',
								'child'=>[
											['child_name'=>'修改密码','child_url'=>'site/update-password'],
											['child_name'=>'修改资料','child_url'=>'member/update'],
										]
							],
							[
								'name'=>'商城产品',
								'url'=>'#',
								'child'=>[
											['child_name'=>'购物产品','child_url'=>'product/index',],
											['child_name'=>'查看购物车','child_url'=>'shop-car/car'],
											['child_name'=>'结算购物车','child_url'=>'order/confirm-order'],
											['child_name'=>'物流信息','child_url'=>'order/index'],
										]
							],
							// [
							// 	'name'=>'系统邮箱',
							// 	'url'=>'#',
							// 	'child'=>[
							// 				['child_name'=>'收件箱','child_url'=>'message/inbox',],
							// 				['child_name'=>'发件箱','child_url'=>'message/outbox',],
							// 				['child_name'=>'发邮件','child_url'=>'message/write',],
							// 			]
							// ],
							[
								'name'=>'问题反馈',
								'url'=>'#',
								'child'=>[
											['child_name'=>'反馈列表','child_url'=>'report/index',],
											['child_name'=>'提交反馈','child_url'=>'report/create',],
										]
							],
							// [
							// 	'name' => '安全退出',
							// 	'url' => 'site/logout',
							// 	'child'=>[],
							// ],

						];

	public static function urlTo($url=NULL)
	{
		if (empty($url) || $url == '#') {
			// return '#';
			return 'javascript:void(0)';
		}
		else {
			return Url::to([$url]);
		}
	}
	public static function getTouting(){
		$member=yii::$app->user->identity->is_agent;
		if($member){
			return self::$title;
		}
		else{

			$array=self::$title;
			unset($array[4]['child'][1]);
			unset($array[4]['child'][2]);
			$array[4]['child'][1]=['child_name'=>'申请服务中心','child_url'=>'ap-agent/index',];
			return $array;
		}
	}

}


