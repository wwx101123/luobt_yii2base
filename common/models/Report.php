<?php
namespace common\models;
use Yii;
/**
--
-- 表的结构 `ld_report`
--

CREATE TABLE `ld_report` (
`id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `update_time` int(11) NOT NULL,
  `is_pay` tinyint(4) NOT NULL,
  `is_read` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='反馈表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ld_report`
--
ALTER TABLE `ld_report`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ld_report`
--
ALTER TABLE `ld_report`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

 */
class Report extends \yii\db\ActiveRecord
{
    const NOHUIFU = 0; //没回复
    const YIHUIFU = 1; //已回复

    const JINXING = 0; //进行中...
    const JIESHU = 1; //已结束

    public static $states = [
        self::NOHUIFU => '待回复',
        self::YIHUIFU => '已回复',
    ];

    public static $status_arr = [
        self::JINXING => '进行中',
        self::JIESHU => '已结束',
    ];
    public static function tableName()
    {
        return '{{%report}}';
    }
    public $user_name; //会员昵称（反馈的会员）
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['title', 'content', 'user_id', 'create_time', 'status', 'update_time', 'is_pay', 'is_read'], 'required'],
            [['title'],'required'],
            [['content','user_name'], 'string'],
            [['user_id', 'create_time', 'status', 'update_time', 'is_pay', 'is_read'], 'integer'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', '反馈标题'),
            'content' => Yii::t('app', '反馈内容'),
            'user_id' => Yii::t('app', '会员编号'),
            'create_time' => Yii::t('app', '反馈时间'),
            'status' => Yii::t('app', '状态'),
            'update_time' => Yii::t('app', 'Update Time'),
            'is_pay' => Yii::t('app', 'Is Pay'),
            'is_read' => Yii::t('app', '消息'),
        ];
    }

    // 关联member表
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'user_id']);
    }

    /**
     * 反馈状态
     */
    public static function get_status($state)
    {
        return Yii::t('app', self::$states[$state]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {  
            $this->create_time = time();
            return true;
        } else {
            return false;
        }
    }

    public function addMsg()
    {   
        $model = new ReportMsg;
        $model->report_id = $this->id;
        $model->name = Member::getName($this->user_id);
        $model->content = $this->content;
        $model->create_time = time();
        $model->save();
        return true;
    }

    public function getReportMsg()
    {
        return $this->hasMany(ReportMsg::className(), ['report_id' => 'id']);
    }

}
