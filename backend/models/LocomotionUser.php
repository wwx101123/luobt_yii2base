<?php 
namespace backend\models;

use yii;
use yii\base\Model;
use common\models\Member;
use common\models\Relationship;
/**
* 	
*/
class LocomotionUser extends Model
{
	public $move_user;
	public $to_user;
	public $area;
    public static $arr = ['左区', '中区', '右区'];

	public function rules()
    {
        return [

            [['move_user', 'to_user'], 'required'],
            [['move_user','to_user'],'string'],
            ['move_user', 'checkUser'],

            ['area','checkArea'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'move_user' => '需移动会员',
            'to_user' => '移动到该会员下面',
            'area' =>'区域',
        ];
    }
    public function checkUser(){
    	$M_model = Member::find()->where(['username'=>$this->move_user])->one(); 
    	if(empty($M_model)){
            return $this->addError('move_user','会员不存在'.$this->move_user);
    	}

    	$T_model = Member::find()->where(['username'=>$this->to_user])->one(); 
    	if(empty($T_model)){
    		return $this->addError('to_user','会员不存在');
    	}

        if($this->area){
     		$model = Relationship::find()->where(['member_id'=>$T_model->id])->andwhere(['like','p_path',$M_model->id.','])->one();
     		if($model){
                return $this->addError('move_user','不允许移动到自己团队下层');
     		}
        }else{
             $model = Relationship::find()->where(['member_id'=>$T_model->id])->andwhere(['like','re_path',$M_model->id.','])->one();
            if($model){
                return $this->addError('move_user','不允许移动到自己团队下层');
            }
        }
        
        if($this->move_user==$this->to_user){
            return $this->addError('move_user','错误的操作');
        }
    }
    public function checkArea(){
    	$father_model= Member::find()->where(['username'=>$this->to_user])->one();
    	if($father_model){
	    	$Relation = Relationship::find()->where(['father_id' =>$father_model->id])->andwhere(['area'=>$this->area])->one();
	    	if($Relation){
	    		return $this->addError('area','该位置已有会员存在');
	    	}
    	}
    }
    //网络图移动
    public function Move(){
    	$M_model = Member::find()->where(['username'=>$this->move_user])->one(); 
    	$T_model = Member::find()->where(['username'=>$this->to_user])->one(); 
        $model = Relationship::find()->where(['member_id'=>$M_model->id])->one();//需要移动的会员
        if($model){
            $old_p_path=$model->p_path;
            $model->father_id = $T_model->id;
            $model->area = $this->area;
            $relation = Relationship::find()->where(['member_id'=>$T_model->id])->one();
            if(empty($relation->p_path)){
                $model->p_path = $T_model->id.',';
            }else{
                $model->p_path = $relation->p_path.$T_model->id.',';
            }
            if(!$model->update()){
                throw new \Exception("移动失败", 1);
                
            }else{
                $this->PLevel($model->member_id);

            }
            $models = Relationship::find()->where(['like','p_path',$M_model->id.','])->all();//查找需要移动的会员和旗下的所有会员
            if($models){
                foreach ($models as $key => $v) {
                    $v->p_path=str_replace($old_p_path,$model->p_path,$v->p_path);
                    if(!$v->save()){
                        throw new \Exception("移动失败", 1);
                    }else{
                        $this->PLevel($model->member_id);
                    }
                }
            }
        }
    }

    //推荐图移动
    public function reMove(){

        $M_model = Member::find()->where(['username'=>$this->move_user])->one();

        $T_model = Member::find()->where(['username'=>$this->to_user])->one(); 
        $model = Relationship::find()->where(['member_id'=>$M_model->id])->one();//需要移动的会员
        if($model){
            $old_re_path=$model->re_path;
            $old_re_id = $model->re_id;
            $model->re_id = $T_model->id;
            $relation = Relationship::find()->where(['member_id'=>$T_model->id])->one();
            if(empty($relation->re_path)){
                $model->re_path = $T_model->id.',';
            }else{
                $model->re_path = $relation->re_path.$T_model->id.',';
            }

            if(!$model->update()){
                throw new \Exception("移动失败", 1);
            }else{
                $this->ReLevel($model->member_id);
            }
            Relationship::updateAllCounters(['re_nums'=>-1],['member_id'=>$old_re_id]);
            Relationship::updateAllCounters(['re_nums'=>1],['member_id'=>$relation->member_id]);
            $models = Relationship::find()->where(['like','re_path',$M_model->id.','])->all();//查找需要移动的会员和旗下的所有会员
            //修改旗下会员推荐路径
            if($models){
                foreach ($models as $key => $v) {
                    $v->re_path=str_replace($old_re_path,$model->re_path,$v->re_path);
                    if(!$v->save()){
                        throw new \Exception("移动失败", 1);
                    }else{
                        $this->ReLevel($v->member_id);
                    }
                }
            }

        }
    }

    //绝对代数
    public function ReLevel($id){
        $model= Relationship::find()->where(['member_id'=>$id])->one();
        $num= explode(',',$model->re_path);
        $count = count($num)-1;
        $model->re_level= $count;
        if($model->save()){
            return true;
        }else{
            throw new \Exception("Error Processing Request", 1);
            
        }
    }
    //绝对层数
  public function PLevel($id){
        $model= Relationship::find()->where(['member_id'=>$id])->one();
        $num= explode(',',$model->p_path);
        $count = count($num)-1;
        $model->p_level= $count;
        if($model->save()){
            return true;
        }else{
            throw new \Exception("Error Processing Request", 1);
            
        }
    }

}

