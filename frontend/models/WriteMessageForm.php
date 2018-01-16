<?php
namespace frontend\models;

use common\models\Member;
use yii\base\Model;
use Yii;
use common\models\Message;

/**
 * Signup form
 */
class WriteMessageForm extends Model
{
    public $tusername;
    public $tuid;
    public $fusername;
    public $fuid;
    public $rdt;
    public $title;
    public $content;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tusername', 'title', 'content'], 'filter', 'filter' => 'trim'],
            [['tusername', 'title', 'content'], 'required'],
            [['fusername', 'tusername'], 'string', 'max' => 32],

            ['tusername', 'validateTUsername'],
        ];
    }

    public function validateTUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Member::findByUsername($this->tusername);
            if (!$user) {
                $this->addError($attribute, '会员不存在.');
            }else{
                $this->tuid = $user->id;
            }
        }
    }

    public function write()
    {
        if (!$this->validate()) {
            return null;
        }
        $model = new Message();
        $model->fuid = $this->fuid;
        $model->fusername = $this->fusername;
        $model->tuid = $this->tuid;
        $model->tusername = $this->tusername;
        $model->title = $this->title;
        $model->content = $this->content;
        $model->rdt = time();
        return $model->save();
    }

    public function attributeLabels()
    {
        return [
            'tusername'=>'会员编号',
            'title'=>'标题',
            'content'=>'内容',
        ];
    }
}
